# Panna Wild Tours Child Theme

This child theme is built for the Panna Wild Tour website and extends the Getwid Base WordPress theme with branding, layout, and content-specific styling.

## Included customizations

- Theme metadata updated to reflect the live site and brand.
- Child theme support for translations and modern HTML5 features.
- Custom styles in `css/wildtours.css` to refine typography, header layout, buttons, and widget surfaces.
- Child theme JavaScript in `js/wildtours.js` for smooth in-page navigation.
- Theme structure uses `Template: getwid-base` as the parent theme.

## Brand information

- Site: https://www.pannawildtour.com/
- Tagline: Live the Nature!
- Contact: Support@pannawildtour.com
- Phone: +91 992184....
- Address: Panna Wild Tour, Madla Gate, Madla, Panna, Madhya Pradesh, India

## Best practices applied

- Uses WordPress hooks and action callbacks for setup and asset loading.
- Loads the parent theme stylesheet before the child theme stylesheet.
- Uses file modification time for custom asset versioning to aid browser cache invalidation.
- Includes safe text output for translation-ready text strings.
- Uses CSS custom properties for consistent color branding and accessible focus styles.
- Includes a starter translation template at `languages/wildtours.pot`.

## Development notes

- Place any additional child theme templates in the root folder.
- Add translations to the `languages/` directory when ready.
- Keep custom markup and presentation logic out of the parent theme by using child theme helpers.

