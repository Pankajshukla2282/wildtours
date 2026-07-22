<?php
/**
 * Generate translation files for the Panna Wild Tours theme.
 *
 * Usage:
 *   php generate-translations.php --pot
 *   php generate-translations.php --compile
 *   php generate-translations.php --all
 *
 * This script extracts translatable strings from PHP source files and writes a
 * POT template, then compiles any PO files in the languages/ directory into MO
 * files for WordPress.
 */

if ( php_sapi_name() !== 'cli' ) {
    exit( "This script must be run from the command line.\n" );
}

$sourceDirs = [__DIR__, __DIR__ . '/inc'];
$textDomain = 'wildtours';
$potFile = __DIR__ . '/languages/wildtours.pot';
$languagesDir = __DIR__ . '/languages';

$arguments = array_slice($argv, 1);
$mode = 'all';
if (in_array('--pot', $arguments, true)) {
    $mode = 'pot';
} elseif (in_array('--compile', $arguments, true)) {
    $mode = 'compile';
}

if (!is_dir($languagesDir)) {
    mkdir($languagesDir, 0755, true);
}

if ($mode === 'pot' || $mode === 'all') {
    $entries = extract_strings_from_sources($sourceDirs, $textDomain);
    write_pot($potFile, $entries);
    echo "Generated POT file: {$potFile}\n";
}

if ($mode === 'compile' || $mode === 'all') {
    $compiled = compile_po_files($languagesDir);
    foreach ($compiled as $file) {
        echo "Compiled MO file: {$file}\n";
    }
}

exit(0);

function extract_strings_from_sources(array $dirs, string $domain): array {
    $pattern = '/\b(?:__|_e|_x|_ex|esc_html__|esc_html_e|esc_attr__|esc_attr_e)\(\s*(?:[\"\\\'])(.*?)(?:[\"\"])\s*,\s*(?:[\"\\\'])(.*?)\1\s*\)/s';
    $functions = ['__', '_e', '_x', '_ex', 'esc_html__', 'esc_html_e', 'esc_attr__', 'esc_attr_e'];
    $strings = [];

    foreach ($dirs as $dir) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }
            $path = $file->getPathname();
            if (! preg_match('/\.php$/', $path)) {
                continue;
            }
            $content = file_get_contents($path);
            if ($content === false) {
                continue;
            }
            foreach ($functions as $func) {
                $regex = '/\b' . preg_quote($func, '/') . '\(\s*([\"\\\'])(.*?)\1\s*(?:,\s*([\"\\\'])(.*?)\3\s*)?\)/s';
                if (preg_match_all($regex, $content, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $msgid = stripcslashes($match[2]);
                        if ($msgid === '') {
                            continue;
                        }
                        $strings[$msgid] = $msgid;
                    }
                }
            }
        }
    }

    ksort($strings, SORT_STRING);
    return $strings;
}

function write_pot(string $file, array $entries): void {
    $header = "msgid \"\"\nmsgstr \"\"\n";
    $header .= "\"Project-Id-Version: Panna Wild Tours 1.0.1\\n\"\n";
    $header .= "\"POT-Creation-Date: " . date('Y-m-d H:iO') . "\\n\"\n";
    $header .= "\"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n\"\n";
    $header .= "\"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n\"\n";
    $header .= "\"Language-Team: English <LL@li.org>\\n\"\n";
    $header .= "\"Language: en\\n\"\n";
    $header .= "\"MIME-Version: 1.0\\n\"\n";
    $header .= "\"Content-Type: text/plain; charset=UTF-8\\n\"\n";
    $header .= "\"Content-Transfer-Encoding: 8bit\\n\"\n";
    $header .= "\"Plural-Forms: nplurals=2; plural=(n != 1);\\n\"\n";
    $header .= "\"X-Textdomain: wildtours\\n\"\n\n";

    $lines = [$header];
    foreach ($entries as $msgid) {
        $lines[] = 'msgid "' . addcslashes($msgid, "\"\\\n\r\t") . '"';
        $lines[] = 'msgstr ""';
        $lines[] = '';
    }

    file_put_contents($file, implode("\n", $lines));
}

function compile_po_files(string $dir): array {
    $compiled = [];
    $iterator = new DirectoryIterator($dir);
    foreach ($iterator as $fileinfo) {
        if ($fileinfo->isFile() && preg_match('/\.po$/', $fileinfo->getFilename())) {
            $poFile = $fileinfo->getPathname();
            $moFile = $dir . '/' . basename($poFile, '.po') . '.mo';
            compile_po_to_mo($poFile, $moFile);
            $compiled[] = $moFile;
        }
    }
    return $compiled;
}

function compile_po_to_mo(string $poFile, string $moFile): void {
    $entries = parse_po_file(file_get_contents($poFile));
    $translations = $entries['translations'];
    ksort($translations, SORT_STRING);

    $ids = [];
    $strs = [];
    foreach ($translations as $msgid => $msgstr) {
        $ids[] = $msgid;
        $strs[] = $msgstr;
    }

    $offset = 7 * 4;
    $originals = '';
    $translationsString = '';
    $origOffsets = [];
    $transOffsets = [];

    foreach ($ids as $id) {
        $origOffsets[] = [strlen($originals), strlen($id) + 1];
        $originals .= $id . "\0";
    }
    foreach ($strs as $str) {
        $transOffsets[] = [strlen($translationsString), strlen($str) + 1];
        $translationsString .= $str . "\0";
    }

    $origTableOffset = $offset;
    $transTableOffset = $offset + count($ids) * 8;
    $origStringsOffset = $transTableOffset + count($ids) * 8;

    $header = pack('Iiiiiii', 0x950412de, 0, count($ids), $origTableOffset, $transTableOffset, 0, $origStringsOffset);

    $data = $header;
    foreach ($origOffsets as [$length, $offset]) {
        $data .= pack('II', $length, $offset);
    }
    foreach ($transOffsets as [$length, $offset]) {
        $data .= pack('II', $length, $offset);
    }
    $data .= $originals;
    $data .= $translationsString;

    file_put_contents($moFile, $data);
}

function parse_po_file(string $content): array {
    $lines = preg_split('/\r\n|\r|\n/', $content);
    $entries = ['translations' => []];
    $current = ['msgid' => null, 'msgstr' => null];
    $lastField = null;

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        if (preg_match('/^msgid\s+"(.*)"$/', $line, $m)) {
            if ($current['msgid'] !== null) {
                $entries['translations'][$current['msgid']] = $current['msgstr'] ?? '';
                $current = ['msgid' => null, 'msgstr' => null];
            }
            $current['msgid'] = stripcslashes($m[1]);
            $lastField = 'msgid';
            continue;
        }

        if (preg_match('/^msgstr\s+"(.*)"$/', $line, $m)) {
            $current['msgstr'] = stripcslashes($m[1]);
            $lastField = 'msgstr';
            continue;
        }

        if (preg_match('/^"(.*)"$/', $line, $m) && $lastField !== null) {
            $current[$lastField] .= stripcslashes($m[1]);
            continue;
        }
    }

    if ($current['msgid'] !== null) {
        $entries['translations'][$current['msgid']] = $current['msgstr'] ?? '';
    }

    return $entries;
}
