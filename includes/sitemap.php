<?php
/**
 * Created by PhpStorm.
 * User: seotoaster
 * Date: 4/7/15
 * Time: 4:44 PM
 */

class SeosfwmSitemap extends SeosambaWebmasters {

    const SMFEED_CHANGEFREEQ    = 'daily';

    const SITEMAP_PAGE_LIMIT    = 1000;

    private $_sitemap_shift     = 0;

    public function __construct() {
        parent::__construct();
    }

    protected function _get_sitemap_posts() {
        return $this->_get_page_objects( SeosambaWebmasters::PAGE_TYPE_POST );
    }

    protected function _get_sitemap_pages() {
        return $this->_get_page_objects( SeosambaWebmasters::PAGE_TYPE_PAGE );
    }

    protected function _get_page_objects( $where ) {
        global $wpdb;

        if( !empty( $this->_sitemap_shift ) ) {
            $select = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix .
                "posts WHERE post_type = '%s' AND post_status = 'publish' " .
                "ORDER BY 'ID' ASC LIMIT %d,%d",
                $where,
                $this->_sitemap_shift,
                self::SITEMAP_PAGE_LIMIT
            );
        }else {
            $select = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix .
                "posts WHERE post_type = '%s' AND post_status = 'publish' " .
                "ORDER BY 'ID' ASC LIMIT %d",
                $where,
                self::SITEMAP_PAGE_LIMIT
            );
        }

        return $wpdb->get_results( $select, ARRAY_A );
    }

    protected function _count_objects( $where ) {
        global $wpdb;
        $select = $wpdb->prepare( "SELECT COUNT(*) FROM " . $wpdb->prefix .
            "posts WHERE post_type = '%s' AND post_status = 'publish' ", $where );

        $result = $wpdb->get_col( $select );

        return ( !empty( $result ) ) ? intval( $result[0] ) : 0;
    }

    protected function _get_count_pages() {
        return $this->_count_objects( SeosambaWebmasters::PAGE_TYPE_PAGE );
    }

    protected function _get_count_posts() {
        return $this->_count_objects( SeosambaWebmasters::PAGE_TYPE_POST );
    }

    protected function _get_sitemap_categories() {
        global $wpdb;

        if( !empty( $this->_sitemap_shift ) ) {
            $select = $wpdb->prepare( "SELECT " . $wpdb->prefix . "terms.term_id as ID FROM " .
                $wpdb->prefix . "terms LEFT JOIN " . $wpdb->prefix . "term_taxonomy " .
                " ON " . $wpdb->prefix . "terms.term_id = "  . $wpdb->prefix .
                "term_taxonomy.term_id WHERE taxonomy = 'category' ORDER BY parent, slug LIMIT %d,%d",
                $this->_sitemap_shift,
                self::SITEMAP_PAGE_LIMIT
            );
        }else {
            $select = $wpdb->prepare( "SELECT " . $wpdb->prefix . "terms.term_id as ID FROM " .
                $wpdb->prefix . "terms LEFT JOIN " . $wpdb->prefix . "term_taxonomy " .
                " ON " . $wpdb->prefix . "terms.term_id = "  . $wpdb->prefix .
                "term_taxonomy.term_id WHERE taxonomy = 'category' ORDER BY parent, slug LIMIT %d",
                self::SITEMAP_PAGE_LIMIT
            );
        }

        return $wpdb->get_results( $select, ARRAY_A );
    }

    protected function _get_categories_count() {
        global $wpdb;
        $select = "SELECT COUNT(*) FROM " . $wpdb->prefix . "terms LEFT JOIN " . $wpdb->prefix . "term_taxonomy " .
            " ON " . $wpdb->prefix . "terms.term_id = "  . $wpdb->prefix . "term_taxonomy.term_id WHERE taxonomy = 'category' 
            ORDER BY parent, slug";

        $result = $wpdb->get_col( $select );

        return ( !empty( $result ) ) ? intval( $result[0] ) : 0;
    }

    public function mojo_sitemap() {
        preg_match( '/sitemap(index|post|page|category)(\d{0,}?)\.xml$/', $_SERVER['REQUEST_URI'], $sitemap_match );

        if ( !empty( $sitemap_match[1] ) ) {

            if( !empty( $sitemap_match[2] ) ) {
                $this->_sitemap_shift = $sitemap_match[2] * self::SITEMAP_PAGE_LIMIT;
            }

            ob_end_clean();
            header( 'Content-type: text/xml' );
            echo '<?xml version="1.0" encoding="UTF-8"?>';

            switch ( $sitemap_match[1] ) {
                case 'index':
                    echo $this->_compose_sitemap_index_xml();
                    break;
                case 'post':
                    echo $this->_compose_sitemap_post_xml();
                    break;
                case 'page':
                    echo $this->_compose_sitemap_page_xml();
                    break;
                case 'category':
                    echo $this->_compose_sitemap_category_xml();
                    break;
            }

            ob_end_flush();
            exit(1);
        }
    }

    private function _compose_sitemap_index_xml() {
        $sitemaps = array('post' => 1, 'page' => 1, 'category' => 1);

        $pages_sitemap_number     = $this->_get_count_pages() / self::SITEMAP_PAGE_LIMIT;
        $posts_sitemap_number     = $this->_get_count_posts() / self::SITEMAP_PAGE_LIMIT;
        $categories_sitemap_count =  $this->_get_categories_count() / self::SITEMAP_PAGE_LIMIT;

        if( $pages_sitemap_number > 1 ) {
            $sitemaps['page'] = $pages_sitemap_number;
        }

        if( $posts_sitemap_number > 1 ) {
            $sitemaps['post'] = $posts_sitemap_number;
        }

        if( $categories_sitemap_count > 1 ) {
            $sitemaps['category'] = $categories_sitemap_count;
        }

        $sitemap_index = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ( $sitemaps as $key => $sitemap_number ) {

            for( $i = 0; $i < $sitemap_number; $i++ ) {
                $current_sitemap_number = ( $i === 0 ) ? '' : $i;
                $sitemap_index .= '<sitemap>
                                <loc>' . site_url() . '/sitemap' . $key . $current_sitemap_number . '.xml</loc>
                                <lastmod>' . date('c', time()) . '</lastmod>
                              </sitemap>';
            }
        }

        return $sitemap_index . '</sitemapindex>';
    }

    private function _compose_sitemap_category_xml() {
        $categories = $this->_get_sitemap_categories();
        return $this->_compose_xml($categories, true);
    }

    private function _compose_sitemap_page_xml() {
        $sitemap_objects = array();
        $pages = $this->_get_sitemap_pages();

        if ( !empty( $this->_index_object ) && $this->_index_object['post_type'] === SeosambaWebmasters::PAGE_TYPE_PAGE ) {
            array_push( $sitemap_objects, $this->_index_object );
        }

        if ( !empty($pages) ) {
            $sitemap_objects = array_merge( $sitemap_objects, $pages );
        }

        return $this->_compose_xml($sitemap_objects);
    }

    private function _compose_sitemap_post_xml() {
        $sitemap_objects = array();
        $posts = $this->_get_sitemap_posts();

        if ( !empty( $this->_index_object ) && $this->_index_object['post_type'] === SeosambaWebmasters::PAGE_TYPE_POST ) {
            array_push( $sitemap_objects, $this->_index_object );
        }

        if ( !empty($posts) ) {
            $sitemap_objects = array_merge( $sitemap_objects, $posts );
        }

        return $this->_compose_news_xml( $sitemap_objects );
    }

    private function _compose_xml($sitemap_objects, $is_category = false) {
        $urls = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
                xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">';

        if( !$is_category && empty( $this->_sitemap_shift ) ) {
            $urls .= '<url>
                    <loc>' . site_url() . '/</loc>
                    <lastmod>' . date('c', time()) . '</lastmod>
                    <changefreq>' . self::SMFEED_CHANGEFREEQ . '</changefreq>
                    <priority>1</priority>
                </url>';
        }

        if ( !empty( $sitemap_objects ) ) {

            foreach ( $sitemap_objects as $object ) {
                $priority = ( !empty( $this->_index_object ) && (integer) $object['ID'] === (integer) $this->_index_object['ID'] ) ? '1' : '0.8';

                if( !empty( $object['post_name'] ) ) {
                    $url = get_permalink( $object['ID'] );
                }else {
                    $url = site_url() . '/' . $this->_get_category_url( $object['ID'] );
                }

                $urls .= '<url>
                            <loc>' . $url . '</loc>
                            <lastmod>' . date( 'c', time() ) . '</lastmod>
                            <changefreq>' . self::SMFEED_CHANGEFREEQ . '</changefreq>
                            <priority>' . $priority . '</priority>
                        </url>';
            }
        }

        return $urls . '</urlset>';
    }

    private function _compose_news_xml( $sitemap_objects ) {
        // Use get_bloginfo('language') OR get_locale() to get current website language
        $language = substr( get_bloginfo('language'), 0, 2 );
        $urls = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
                         xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';

        if ( !empty( $sitemap_objects ) ) {

            foreach ( $sitemap_objects as $object ) {
                $url = get_permalink( $object['ID'] );
                $post_title = htmlentities($object['post_title'], ENT_QUOTES, "UTF-8");
                $urls .= '<url>
                            <loc>' . $url . '</loc>
                            <news:news>
                                <news:publication>
                                    <news:name>' . $post_title . '</news:name>
                                    <news:language>' . $language . '</news:language>
                                </news:publication>
                                <news:publication_date>' . date( 'c', strtotime($object['post_modified']) ) . '</news:publication_date>
                                <news:title>' . $post_title . '</news:title>
                                <news:keywords>' . $object['meta_keywords'] . '</news:keywords>
                            </news:news>
                            
                        </url>';
            }
        }
        return $urls . '</urlset>';
    }

}