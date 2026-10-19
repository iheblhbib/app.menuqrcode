<?php

include('../config.menuqrcode/config.php');

$object = new application();

$market_id = 293;

$menu = [];

/* LANGUAGES */
$object->query = "
    SELECT *
    FROM langue
    WHERE
        id = 8
        OR (
            statut = 'Activer'
            AND id IN (
                SELECT langue
                FROM market_langue
                WHERE market = '$market_id'
                AND etat = '1'
            )
        )
";

$result_langues = $object->get_result();

foreach($result_langues as $lang)
{
    $langue_id   = $lang['id'];
    $langue_code = $lang['code'];

    if($langue_code ==  $_GET['langue']){
        $menu = [
            'categories' => [],
            'ui' => []
        ];
    
        /* MARKET */
        $object->query = "
            SELECT *
            FROM market
            WHERE id = '$market_id'
            LIMIT 1
        ";
    
        $market_result = $object->get_result();
        $market = [];
    
        foreach($market_result as $row_market)
        {
            $market = $row_market;
        }
    
        /* LEVEL 1 : CATEGORIE_SUB */
        $object->query = "
            SELECT
                cs.*,
                COALESCE(lcs.libelle, cs.libelle) AS label
            FROM categorie_sub cs
            LEFT JOIN langue_categorie_sub lcs
                ON lcs.categorie = cs.id
                AND lcs.langue = '$langue_id'
            WHERE cs.market = '$market_id'
            AND cs.statut = 'Activer'
            ORDER BY cs.order_categorie ASC
        ";
    
        $result_sections = $object->get_result();
    
        foreach($result_sections as $section)
        {
            $main_category = [
                'id' => 'section_'.$section['id'],
                'label' => $section['label'],
                'icon' => $section['icon'] ?? '',
                'image' => $section['image'] ?? '',
                'subcategories' => []
            ];
    
            /* LEVEL 2 : CATEGORIE */
            $object->query = "
                SELECT
                    c.*,
                    COALESCE(lc.libelle, c.libelle) AS label
                FROM categorie c
                LEFT JOIN langue_categorie lc
                    ON lc.categorie = c.id
                    AND lc.langue = '$langue_id'
                WHERE c.categorie = '".$section['id']."'
                AND c.statut = 'Activer'
                ORDER BY c.order_categorie ASC
            ";
    
            $result_categories = $object->get_result();
    
            foreach($result_categories as $cat)
            {
                $subcategory = [
                    'id' => 'cat_'.$cat['id'],
                    'label' => $cat['label'],
                    'image' => $cat['image'],
                    'items' => []
                ];
    
                /* LEVEL 3 : ARTICLES */
                $object->query = "
                    SELECT
                        a.*,
                        COALESCE(la.libelle, a.libelle) AS article_name,
                        COALESCE(la.description, a.description) AS article_description,
                        COALESCE(la.prix, a.prix) AS article_price
                    FROM article a
                    LEFT JOIN langue_article la
                        ON la.article = a.id
                        AND la.langue = '$langue_id'
                    WHERE a.categorie = '".$cat['id']."'
                    AND a.statut = 'Activer'
                    ORDER BY a.order_categorie ASC
                ";
    
                $result_articles = $object->get_result();
    
                foreach($result_articles as $article)
                {
                    $subcategory['items'][] = [
                        'name'  => $article['article_name'],
                        'price' => $article['article_price'],
                        'desc'  => $article['article_description'],
                        'image' => $article['image']
                    ];
                }
    
                $main_category['subcategories'][] = $subcategory;
            }
    
            $menu['categories'][] = $main_category;
        }
    
        /* UI */
        $menu['ui'] = [
            'title' => $market['market'],
            'subtitle' => '',
            'menu_btn' => 'MENU',
    
            'back' => (
                $langue_code == 'fr' ? 'Retour' :
                ($langue_code == 'ar' ? 'رجوع' : 'Back')
            ),
    
            'close' => (
                $langue_code == 'fr' ? 'Fermer' :
                ($langue_code == 'ar' ? 'إغلاق' : 'Close')
            ),
    
            'lang_label' => (
                $langue_code == 'fr' ? 'Langue' :
                ($langue_code == 'ar' ? 'اللغة' : 'Language')
            ),
    
            'follow' => (
                $langue_code == 'fr' ? 'Suivez-nous' :
                ($langue_code == 'ar' ? 'تابعنا' : 'Follow us')
            ),
    
            'info' => (
                $langue_code == 'fr' ? 'Informations' :
                ($langue_code == 'ar' ? 'معلومات' : 'Information')
            ),
    
            'address_label' => (
                $langue_code == 'fr' ? 'Adresse' :
                ($langue_code == 'ar' ? 'العنوان' : 'Address')
            ),
    
            'reservations' => (
                $langue_code == 'fr' ? 'Réservations' :
                ($langue_code == 'ar' ? 'الحجوزات' : 'Reservations')
            ),
    
            'phone' => (
                $langue_code == 'fr' ? 'Téléphone' :
                ($langue_code == 'ar' ? 'هاتف' : 'Phone')
            ),
    
            'email' => 'Email',
    
            'address' => $market['address'],
            'phone_number' => $market['tel'],
            'email_address' => $market['email']
        ];
    }
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode(
    $menu,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES |
    JSON_PRETTY_PRINT
);