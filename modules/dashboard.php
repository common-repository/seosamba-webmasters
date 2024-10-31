<?php
/**
 * Created by Oleg Malii
 * Date: 14.03.17 16:41
 */
function seosfwm_dashboard() {
    // embed the javascript file that makes the AJAX request
    wp_enqueue_script('create-ajax-request', SEOSFWM_ROOT_FOLDER_URL . 'assets/js/dashboard.js', array('jquery'));
    ?>
    <div id="sambapress" class="container" style="background-color: #FFF; margin: 0;">
        <div class="header-line">
            <header class="grid_12 alpha omega">
                <a href="https://www.seosamba.com/" target="_blank" class="logo fl-left mt5px">
                    <img height="80px" src="<?php echo SEOSFWM_ROOT_FOLDER_URL; ?>assets/images/samba-logo.png" />
                </a>
                <div class="text-left header-title grid_8 fs24" style="padding-left: 10px; margin-top: 30px;">
                    <?php echo SeosambaWebmasters::PLUGIN_NAME; ?>
                </div>
            </header>
        </div>

        <div class="grid_8 mt0px alpha">
            <div class="mojo-nav-tabs grid_12 links icons border ui-tabs ui-widget ui-widget-content ui-corner-all">
                <ul class="mojo-tabs column_2 margin-none ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all mb0px">
                    <li class="ui-state-default ui-corner-top ui-tabs-active" data-tabid="tabs-1">
                        <a href="#tabs-1" class="ui-tabs-anchor">
                            <i class="icon-arrow-right mb5px"></i>
                            <span class="fs16">Getting started</span>
                        </a>

                    </li>
                    <li class="ui-state-default ui-corner-top" data-tabid="tabs-2">
                        <a href="#tabs-2" class="ui-tabs-anchor">
                            <i class="icon-cogs mb5px"></i>
                            <span class="fs16">Features</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div id="tabs-1" class="grid_12">
                <div class="callout mt15px">
                    <h3 class="callout-header">Start using <?php echo SeosambaWebmasters::PLUGIN_NAME; ?></h3>
                    <b class="mt5px mb5px fs14">You will need to do this only once</b>
                    <p class="fs14 mb0px">
                        Sign-up for a free
                        <a href="https://mojo.seosamba.com/register.html" target="_blank">
                            SeoSamba marketing automation platform
                        </a> account with a valid email address, or
                        <a href="https://mojo.seosamba.com/" target="_blank">log in</a> if you already have one.<br />
                        Then please follow the steps below to get a <b>WordPress Access Key</b> for your website.
                    </p>
                    <ul class="list-count-sign">
                        <li class="fs14">
                            Once you've created and activated your free SeoSamba account using the link contained in the welcome email,
                            go to <b>'<i class="icon-cog large"></i> Profile'</b> menu at the top right and click
                            <span class="text-bold">'My account'</span>.
                        </li>
                        <li class="fs14">
                            On <span class="text-bold">'My Account'</span> page, scroll down to find the <b>'<i
                                    class="icon-wordpress large"></i> Add WordPress website'</b> box.
                            Insert your website URL ( <b><u><?php echo site_url();?></u></b> ), and click '<b><i class="icon-plus large"></i> Add'</b> button.
                        </li>
                        <li class="fs14">
                            Then copy <b>WordPress Access Key</b> and insert it here into the box below and click <b>'<i
                                    class="icon-save large"></i> Save access key'</b>
                        </li>
                    </ul>
                    <form action="" method="POST">
                        <label class="text-bold fs14" for="wp-access-key">WordPress Access Key</label>
                        <input id="wp-access-key_nonce" type="hidden" value="<?php echo wp_create_nonce(SeosambaWebmasters::ACCESS_KEY_FIELD)?>" />
                        <input id="wp-access-key" type="text" class="grid_5 alpha omega large"
                               name="<?php echo SeosambaWebmasters::ACCESS_KEY_FIELD; ?>"
                               value="<?php echo SeosambaWebmasters::get_access_key(); ?>"/>
                        <button type="button" id="save-access-key" class="btn large icon-save success"> Save Access
                            Key
                        </button>
                    </form>
                    <p id="save-access-key-response" class="text-center fs14 message mb0px mt5px hidden">
                        <i style="margin-right: 5px;" class="fs18"></i>
                        <b></b>
                    </p>
                </div>
                <div class="callout mt15px">
                    <h3 class="callout-header">Track your site's search performance</h3>
                    <p class="fs14 mb5px">
                        Add and verify your website with Google webmasters search console (formerly known as Google Webmasters Tools).<br />
                        Once connected, SeoSamba will:
                        <ul type="circle">
                            <li class="fs14">
                                Let Google knows regularly about your website content updates;
                            </li>
                            <li class="fs14">
                                Let you find out what key-phrases are driving traffic to your website;
                            </li>
                            <li class="fs14">
                                Help you uncover what key-phrases hold the most potential for your website.
                            </li>
                        </ul>
                    </p>
                    <p class="fs14">
                        It’s simple;
                    </p>

                    <ul class="list-count-sign">
                        <li class="fs14">
                            <b>You will need to do this only once.</b> Go back to your SeoSamba account. At the top of '<b><i class="icon-analytics large"></i> Website
                                Analytics'</b> tab you will find
                            a '<b class="info">Authenticate your SeoSamba account with Google WMT</b>' link. Click it and
                            follow the on-screen instructions to complete authentication.
                        </li>
                        <li class="fs14">
                            <b>You will need to do this for each website you own.</b> After you have successfully authenticated with <b>Google WMT</b> click <b><i class="icon-list2 large"></i></b>
                            icon at the top left corner.
                            A side panel expands, and you find your website there under <b>'<i class="icon-folder3 large"></i> Main project'</b>.<br/>
                        </li>
                        <li class="fs14">
                            Click <b>'<i class="icon-wrench large success"></i> wrench'</b> icon to the right of your website url.
                        </li>
                    </ul>
                    <p class="fs16 text-bold">
                        That’s it. Everything else is automatic.
                    </p>
                    <p class="fs14">
                        You can verify that your website appears in your Google Search Console with a  verified status.
                    </p>
                </div>
            </div>

            <div id="tabs-2" class="grid_12 hidden">
                <div class="callout mt15px">
                    <h3 class="callout-header">Features</h3>
                    <p class="fs14 mb0px">
                        This plugin is a gateway to the "SeoSamba" platform.
                        Once authenticated with your Google Webmasters tools account from your free SeoSamba account, this plugin provides the following services:

                        <ul type="disc">
                            <li class="fs14">
                                Your website is automatically added and verified with Google Webmasters tools.
                                Save your time and efforts. Forget meddling with file upload or tag verification, all of this is handled for you.
                            </li>
                            <li class="fs14">
                                This plugin generates and add a XML sitemap to your WordPress website.
                                We also split url’s into multiple sitemaps and reference them all.
                                You can find your sitemap in your root folder at
                                <a href="<?php echo site_url(); ?>/sitemapindex.xml" target="_blank">
                                    <?php echo site_url(); ?>/sitemapindex.xml
                                </a>
                            </li>
                            <li class="fs14">
                                Your website's sitemap is then automatically uploaded to Google on a weekly basis.
                                It helps your website content being found by Google and increase chances of being quickly indexed.
                            </li>
                            <li class="fs14">
                                And last but not least, this plugin let you <b>check your search engine results rankings with Google</b>.
                                Search engine page results rankings are provided by Google itself and automatically updated every day for your Website.
                            </li>
                        </ul>
                        <p class="fs14">
                            <span class="fs16 text-bold">Forget maintaining keywords lists, let Google reports back to you instead!</span><br />
                            Gain access to comprehensive search reports with SeoSamba for Webmasters with reports for up to 1000 keywords at a time.
                            Flag key phrases you’re targeting with search efforts to access critical queries reports
                            and receive weekly keywords rankings variations alerts in your email inbox.
                        </p>
                        <p class="fs16 text-bold mb0px">
                            Access graphical reports and tables in your online dashboard for:
                        </p>
                        <ul type="disc" class="mt5px">
                            <li class="fs14">Top #1 listings;</li>
                            <li class="fs14">Top 5 rising keywords;</li>
                            <li class="fs14">Newly listed keywords;</li>
                            <li class="fs14">As well as our keywords opportunities finder that tells you what keywords show traffic potential for your website.</li>
                        </ul>

                        <p class="fs14">
                            <b>Download great looking PDF reports</b> or your entire keywords data set to work with a spreadsheet from your free SeoSamba account.
                        </p>
                        <p class="fs14">
                            Watch a search engine ranking tool video presentation
                            <a href="https://www.youtube.com/watch?v=rkDqZJlsYeg" target="_blank">
                                here
                            </a>;
                        </p>
                    </p>
                </div>

                <div class="callout mt15px">
                    <h3 class="callout-header">More Free Services</h3>
                    <p class="fs16 mt10px"><b>Website Analytics</b></p>
                    <p class="fs14">
                        Access comprehensive web traffic reports: analyze traffic metrics side by side with search rankings reports,
                        social marketing and others marketing activities reports.
                    </p>
                    <p class="fs14">
                        Just add our free site analytics services in addition to Google Analytics
                        or any other analytics product you might be using for your website.
                    </p>
                    <p class="fs14 mb0px">
                        <b>You will need to do this for each website you own</b>.<br>
                    </p>
                    <ul class="list-count-sign mt0px mb0px">
                        <li class="fs14">
                            Go to dashboard page in your SeoSamba account and click <b><i class="icon-list2 large"></i></b>
                            icon at the top left corner.<br />
                        </li>
                        <li class="fs14">
                            Find your website, and click on checkbox on the right (next to wrench icon).<br />
                        </li>
                    </ul>
                    <p class="fs14">
                        You’re done!<br />
                        Come back in a few days to view and access your site traffic data.
                    </p>
                    <hr class="larger" />
                    <p class="fs16"><b>Social marketing</b></p>
                    <p class="fs14 text-bold">
                        Manage all your social media in one place.
                    </p>
                    <p class="fs14">
                        Save time by managing all of your social media marketing efforts from a single dashboard.
                        Thanks to the SeoSamba’s platform, you get the tools to manage all your social profiles and
                        automatically find and schedule effective social content in a very nifty social calendar.
                    </p>
                    <p class="fs14 mb0px">
                        Watch video presentations here;
                    </p>
                    <ul type="disc" class="mt0px">
                        <li class="fs14">
                            <a href="https://www.youtube.com/watch?v=hGtUTGv75bY" target="_blank">Content curation tool</a>
                        </li>
                        <li class="fs14">
                            <a href="https://www.youtube.com/watch?v=xJJDk6UDlPA" target="_blank">Social marketing calendar</a>
                        </li>
                    </ul>
                </div>


                <div class="callout mt15px">
                    <h3 class="callout-header">Access more marketing tools</h3>
                    <p class="fs14">
                        SeoSamba’s marketing hub offers additional services on a pay per use service.
                    </p>
                    <ul type="disc">
                        <li class="fs14">
                            Our PR distribution service ensures that your business reaches out to thousands of
                            newsmakers, content aggregators, journalists, specialized bloggers and major print media
                            newsrooms all over the world. Starts at $15 per PR.
                        </li>
                        <li class="fs14">
                            Great to track calls from brochures, TV, radio, web ads, or lead generating websites.
                            SeoSamba's lead tracking solution is available to anyone.
                            You don't even need a website, you just need a web browser to operate it.
                            Local and Toll-free phone numbers available worldwide starting at $5/month.
                        </li>
                    </ul>
                </div>

                <div class="callout mt15px">
                    <h3 class="callout-header">Optimize your website for top search ranking performances</h3>
                    <p class="fs14 mt10px">
                        <b>The larger your site is, the more you need our premium plugin:
                            <a href="<?php echo SeosambaWebmasters::MOJO_URL . SeosambaWebmasters::EXPERT_PLUGIN_LINK;?>"
                               target="_blank">
                                <?php echo SeosambaWebmasters::COMPANY_NAME ?> for WordPress Expert
                            </a>
                        </b>
                    </p>

                    <p class="fs14">
                        Last but not least, SeoSamba provides the ultimate SEO tool to
                        optimize your website for top search rankings performances.<br />
                        The ultimate patent-pending professional tool
                    </p>

                    <p class="fs14 mb0px">
                        Use our custom rules builder and:
                    </p>
                    <ul type="disc" class="mt0px">
                        <li class="fs14">
                            Define how your web pages URL’s should be constructed;
                        </li>
                        <li class="fs14">
                            301 redirections are built automatically for you when you need to adjust URL’s;
                        </li>
                        <li class="fs14">
                            Align your browser titles with page titles, with meta information and more;
                        </li>
                        <li class="fs14">
                            Add variable based content in one-shot to any number of pages;
                        </li>
                        <li class="fs14">
                            Optimize your entire website in one shot from 25 to 1 Million pages. Starting at $9 one-time;
                        </li>
                        <li class="fs14">
                            Keep your site optimized for top search performances forever AND optimize
                            automatically newly created content. Starting at $0.0005 per page per month.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="grid_4 alpha omega">
            <div class="callout mt15px">
                <h3 class="callout-header">Multi-sites owners welcome</h3>
                <p class="fs14">
                    Consolidate all your websites marketing and SEO under one roof.
                </p>
                <p class="fs14 mb0px">
                    SeoSamba for Webmasters is ideal for multi-websites owners:
                </p>
                <ul type="disc">
                    <li class="fs14">Add as many websites as you wish to your free SeoSamba account;
                        WordPress CMS powered websites of course, but also SeoToaster powered websites or any others CMS or Cart solutions;
                    </li>
                    <li class="fs14">Get a bird-eye view of all your sites search ranking performances,
                        and drill down to individual site to find out what key-phrases generate traffic;</li>
                    <li class="fs14">
                        Save tons of time and money by avoiding mistakes and optimizing your website continuously
                        for top Google performances using a rule approach with our
                        <a href="<?php echo SeosambaWebmasters::MOJO_URL . SeosambaWebmasters::EXPERT_PLUGIN_LINK;?>"
                           target="_blank">
                            premium WordPress plugin
                        </a>.
                    </li>
                </ul>
            </div>

            <div class="callout mt15px grid_12 alpha omega">
                <h3 class="text-center mb0px mt25px" style="color: #83b421;">Latest SeoSamba news</h3>
                <div class="grid_12 news-block loading alpha omega" style="min-height: 100px; position:relative;">
                    <ul id="news-list" class="list-bordered"></ul>
                </div>
            </div>
        </div>
    </div>

    <?php
    wp_register_style($handle = 'seosfwm_reset_css', $src = SEOSFWM_ROOT_FOLDER_URL . 'assets/css/reset.css', $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('seosfwm_reset_css');

    wp_register_style($handle = 'seosfwm_content_css', $src = SEOSFWM_ROOT_FOLDER_URL. 'assets/css/content.css', $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('seosfwm_content_css');

    wp_register_style($handle = 'seosfwm_nav_css', $src = SEOSFWM_ROOT_FOLDER_URL . 'assets/css/nav.css', $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('seosfwm_nav_css');

    wp_register_style($handle = 'seosfwm_style_css', $src = SEOSFWM_ROOT_FOLDER_URL . 'assets/css/style.css', $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('seosfwm_style_css');
}
?>