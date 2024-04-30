<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\HomListi_Core;

$url   = urlencode( get_permalink() );
$title = urlencode( get_the_title() );

$defaults = [
	'facebook'  => [
		'url'  => "//www.facebook.com/sharer.php?u=$url",
		'icon' => 'fab fa-facebook-f',
	],
	'twitter'   => [
		'url'  => "//twitter.com/intent/tweet?source=$url&text=$title:$url",
		'icon' => 'fab fa-twitter'
	],
	'linkedin'  => [
		'url'  => "//www.linkedin.com/shareArticle?mini=true&url=$url&title=$title",
		'icon' => 'fab fa-linkedin-in'
	],
	'pinterest' => [
		'url'  => "//pinterest.com/pin/create/button/?url=$url&description=$title",
		'icon' => 'fab fa-pinterest-p'
	],
	'tumblr'    => [
		'url'  => "//www.tumblr.com/share?v=3&u=$url &quote=$title",
		'icon' => 'fab fa-tumblr'
	],
	'reddit'    => [
		'url'  => "//www.reddit.com/submit?url=$url&title=$title",
		'icon' => 'fab fa-reddit'
	],
	'vk'        => [
		'url'  => "//vkontakte.ru/share.php?url=$url",
		'icon' => 'fab fa-vk'
	],
	'whatsapp'  => [
		'url'  => "//api.whatsapp.com/send?text={$title}%0A - {$url}",
		'icon' => 'fab fa-whatsapp'
	],
	'telegram'  => [
		'url'  => "//telegram.me/share/url?url={$url}&text={$title}",
		'icon' => 'fab fa-telegram'
	],
	'email'  => [
		'url'  => "mailto:?subject={$title}",
		'icon' => 'fas fa-envelope'
	],
    'skype' => [
		'url'  => "https://web.skype.com/share?url={$url}",
		'icon' => 'fab fa-skype'
	],
    'pocket' => [
		'url'  => "https://getpocket.com/edit?url={$url}",
		'icon' => 'fab fa-get-pocket'
	],

];

foreach ( $sharer as $key => $value ) {
	if ( ! empty( $value ) ) {
		unset( $defaults[ $value ] );
	}
}

$sharers = apply_filters( 'rdtheme_social_sharing_icons', $defaults, $url, $title );
?>
<ul class="item-social">
	<?php foreach ( $sharers as $key => $sharer ): ?>
        <li class="social-<?php echo esc_attr( $key ); ?>">
            <a href="<?php echo esc_url( $sharer['url'] ); ?>" target="_blank"><i class="<?php echo esc_attr( $sharer['icon'] ); ?>"></i></a>
        </li>
	<?php endforeach; ?>
</ul>