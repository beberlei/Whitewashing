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

class PostType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        if ($options['allow_change_format'] === true) {
            $builder->add('inputFormat', 'choice', array(
                'choices' => array(
                    'rst' => 'ReStructured Text',
                    'html' => 'HTML',
                ),
            ));
        }
        $builder->add('headline', 'text');
        $builder->add('text', 'textarea');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'allow_change_format' => false
        );
    }
}