## Streamium Netflix Theme

S3Bubble is excited to introduce Streamium. This Theme is ideal for any budding directors and filmographer's who are looking to showcase and sell their work. Streamium, like Netflix, is a subscription service making it perfect for anyone with an abundance of video content just waiting to be shared with your following of adoring fans.. If you need support with this theme, please go to https://s3bubble.com

## Latest Release

[Download](https://github.com/s3bubble/Streamium-Netflix-Theme/releases)

## Theme Demo

[Theme Demo](http://streamium.s3bubble.com/)

## Installation & Setup

[Full Video Overview Tutorial](https://s3bubble.com/wp_themes/streamium-netflix-style-wordpress-theme/)

## Optimization & Setup

### Installing updating with git
```
cd wp-content/themes/
git clone https://github.com/s3bubble/Streamium-Netflix-Theme.git Streamium-Netflix-Theme/
```

### Uploading content to AWS and serving via Cloudfront
```
aws s3 sync ./ s3://{aws-bucket}/wp-content/ --exclude "*.php*" --exclude "*.txt*" --exclude "*.md*" --exclude "*.git/*" --exclude "*plugins/*"
```

* Cloudfront distribution you also need to set a invalidation / to clear cache

## Change Log

* Review and rating functionality added
* Video resume where you last viewed added
* Self hosted premium functionality added
* Background video slider
* Option to add video trailers
* S2member intergration
* Customizer updates extra theme styling and Google fonts 
* Change the genre text
* Gtmetrix A PageScore [https://gtmetrix.com/reports/streamiumtheme.com/FUwRTi2Y](https://gtmetrix.com/reports/streamiumtheme.com/FUwRTi2Y)
* Image Optimisation
* Set a maximum value for the homepage carousels in WordPress customizer
* Added page navigation for view all category pages (this is set in the Reading setting option in WP admin)
* Only show recently watched for logged in users
* Meta data added to track recently watched based on user id
* Woocommerce integration 
* Subscritption and Membership intergration
* Login Register all powered by Woocommerce
* Logo update 
* Background updates
* get_option( 'posts_per_page' ) added to loop to allow max posts to be set on carousels

## License

Copyright (c) S3Bubble Ltd licensed under General Public License (GPL)