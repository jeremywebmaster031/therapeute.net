<?php
/**
 * Dynamic css generation file
 *
 */
?>
<?php
 $absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
 $wp_load = $absolute_path[0] . 'wp-load.php';
 require_once($wp_load);

header('Content-type: text/css');
header('Cache-control: must-revalidate');
header( "Content-type: text/css; charset: UTF-8" );

global $listingpro_options;

$primarycolor = $listingpro_options['theme_color'];
$seccolor = $listingpro_options['sec_theme_color'];
?>
.md-header-full-width.lp-header-full-width .lp-add-listing-btn li a {
background-color: <?php echo $primarycolor; ?> !important;
border-color: <?php echo $primarycolor; ?> !important;
}
.md-header-full-width .lp-menu-container .lp-menu>div>ul>li>a:hover {
    color: <?php echo $primarycolor; ?> !important;
}
body.home .md-header-full-width.lp-header-full-width .lp-add-listing-btn li a:hover,
html body .md-header-full-width.lp-header-full-width .lp-add-listing-btn li a:hover,
html body:not(.home) .lp-header-full-width .lp-add-listing-btn ul li a:hover
{
    border-color: <?php echo $primarycolor; ?> !important;
}
.slick-prev:before, .slick-next:before {
    color: <?php echo $primarycolor; ?> !important;
}
.fullwidth-header .header-filter .input-group.width-49-percent {
    border-color: <?php echo $seccolor; ?> !important;
    border-left-color: <?php echo $seccolor; ?> !important;
}
.md-header-full-width.lp-header-full-width .lp-add-listing-btn li a:hover {
    background-color: transparent !important;
    color: <?php echo $primarycolor; ?> !important;
}
.mp-profile-content-details-call-now-btn a:hover {
    background: <?php echo $seccolor; ?>;
    border-color: <?php echo $seccolor; ?>;
    color: <?php echo $primarycolor; ?>;
}

header .fullwidth-header .header-filter .lp-search-icon {
    background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAABeUlEQVRIid3Uv0tWYRjG8XPCFPEnhIGKoQ0uIgYSFLT6RwgOzoFEe3NDS1M4if4BBoGLg5M2OYnSD0knQVAHsQgxik+D96G31+P7nvNqCN1wuOF5ruv6Pjzn4U6SmyqM4TU+4Cu+YxuzeHyV4HYs+LvOcFq1tog7ZcN7sBEBR3iKfqTx3cU09kKzg8Gi4c1YC+Nb9NTQdmEutFvoKAJ4FoZ1NBXQp1gOz8t64ttxJWcYqXuaP7578QBO0V1LOJH9uKLhFd7Z8E7l7d+KPh79fVlAkiSrVRm5gN7ohw0AMk9v3mYGOI7e2QAg8xznbWaAz9HHGgA8iL59qSLe9Q8c1HwNF32t2I2fPFRP/CaE8yUArwq/vhgT+2GYQVpDm2ISv3CC+0VP9BDfArKC4RzNQIwS+Ol8wha+1gSjMcSy+oQlvMNmxfo+PlaMl1KQFjyvCszqC16gDd0RXh5SAevDIzxxPnvSqv2rQwoc4v+DnCB3+F0X5N+E59Vvrt84CynH2rsAAAAASUVORK5CYII=') no-repeat !important;
    background-color: <?php echo $primarycolor; ?> !important;
    background-position: 8px 7px !important;
    border-radius: 4px !important;
    max-height: 38px;
}
.mp-claimed-profile.mp-tooltip .mp-tooltiptext,
.mp-claimed-profile.mp-tooltip .mp-tooltiptext:after
{
    background-color: <?php echo $primarycolor; ?> !important;
}

.md-header-full-width.lp-header-full-width .user_is_not_logged_in .lp-join-now {
background: transparent !important;
border-color: <?php echo $primarycolor; ?> !important;
}
.md-header-full-width.lp-header-full-width .user_is_not_logged_in .lp-join-now a {
color: <?php echo $primarycolor; ?> !important;
}
.md-header-full-width.lp-header-full-width .user_is_not_logged_in .lp-join-now:hover {
background: <?php echo $primarycolor; ?> !important;
}
.md-header-full-width.lp-header-full-width .user_is_not_logged_in .lp-join-now:hover a {
color: #fff !important;
}

.md-header-full-width.lp-header-full-width .lp-add-listing-btn li a {
background: transparent !important;
border: 2px solid transparent !important;
border-color: <?php echo $primarycolor; ?> !important;
color: <?php echo $primarycolor; ?> !important;
}
.md-header-full-width.lp-header-full-width .lp-add-listing-btn li a i {
color: <?php echo $primarycolor; ?> !important;
transition: 300ms ease-in-out;
}
.md-header-full-width.lp-header-full-width .lp-add-listing-btn li a:hover {
background: <?php echo $primarycolor; ?> !important;
color: #fff !important;
}
.md-header-full-width.lp-header-full-width .lp-add-listing-btn li a:hover i {
color: #fff !important;
}

.lp-header-search.lp-header-search-sidebar-style .lp-header-search-form #input-dropdown>ul li:hover {
background-color: #f6f6f6 !important;
color: <?php echo $primarycolor; ?> !important;
border-bottom: 1px solid #f6f6f6 !important;
}

#map-section-expand {
position: fixed;
bottom: 50px;
right: 50px;
z-index: 99;
background: <?php echo $primarycolor; ?>;
width: 50px;
height: 50px;
cursor: pointer;
display: flex;
justify-content: center;
align-items: center;
color: #fff;
border-radius: 50%;
box-shadow: 0px 10px 29px #0a74f344;
font-size: 20px;
}