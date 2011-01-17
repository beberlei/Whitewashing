<?php
/*
 * Whitewashing
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace Whitewashing\BlogBundle\Controller;

class SearchController extends AbstractBlogController
{
    public function queryAction()
    {
        $term = $this->getRequest()->get('q');
        $limit = $this->getRequest()->get('l', 50);
        $page = $this->getRequest()->get('p', 1);

        $session = $this->container->get('zeta.search.session');
        $query = $session->createFindQuery('Whitewashing\Blog\Post');
        $query->where(
            $query->lOr(
                $query->eq('headline', $term),
                $query->eq('text', $term)
            )
        );

        // limit the query and order
        $query->limit($limit, ($page - 1) * $limit);

        $result = $session->find($query);

        return $this->render('BlogBundle:Search:query.twig.html', array(
            'result' => $result,
            'term' => $term,
        ));
    }
}