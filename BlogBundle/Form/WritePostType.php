<?php
/**
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

namespace Whitewashing\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Whitewashing\Blog\Post;

class WritePostType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $postBuilder = $builder->create('post', new PostType(), array('allow_change_format' => $options['allow_change_format']));
        
        foreach ($postBuilder->getTypes() AS $type) {
            $builder->add('post', $type);
        }
        $builder->add('publishStatus', 'choice', array(
            'choices' => array(
                Post::STATUS_DRAFT => 'Draft',
                Post::STATUS_PUBLISHED => 'Published',
            )
        ));
        $builder->add('tags', 'text');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'allow_change_format' => false
        );
    }
}