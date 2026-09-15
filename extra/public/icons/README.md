# PWA Icons

This directory contains icons for the Progressive Web App (PWA).

## Required Icon Sizes

The following icon sizes are required for the PWA to work properly:

- **icon-16x16.png** - 16x16 pixels (favicon)
- **icon-32x32.png** - 32x32 pixels (favicon)
- **icon-72x72.png** - 72x72 pixels (Apple touch icon)
- **icon-96x96.png** - 96x96 pixels (Apple touch icon)
- **icon-128x128.png** - 128x128 pixels (Apple touch icon)
- **icon-144x144.png** - 144x144 pixels (Apple touch icon)
- **icon-152x152.png** - 152x152 pixels (Apple touch icon)
- **icon-192x192.png** - 192x192 pixels (Android/Chrome)
- **icon-384x384.png** - 384x384 pixels (Android/Chrome)
- **icon-512x512.png** - 512x512 pixels (Android/Chrome)

## Icon Guidelines

- Use PNG format for all icons
- Use transparent background where appropriate
- Ensure icons are readable at small sizes
- Use the brand color (#dc3545) as the primary color
- Include the app name or logo in the design

## Creating Icons

You can create these icons using:
1. Online PWA icon generators (like https://www.pwabuilder.com/imageGenerator)
2. Design tools like Figma, Adobe Illustrator, or Canva
3. Command-line tools like ImageMagick

## Example ImageMagick Command

```bash
convert input.png -resize 192x192 icon-192x192.png
convert input.png -resize 512x512 icon-512x512.png
```

## Testing

After adding icons, test the PWA by:
1. Opening the app in Chrome/Edge
2. Opening DevTools (F12)
3. Going to the Application tab
4. Checking the Manifest and Service Workers sections
