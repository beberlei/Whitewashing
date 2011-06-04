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

namespace Whitewashing\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateBlogCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('whitewashing:blog:create')
            ->setDescription('Create a new blog')
            ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $blogRepository = $this->container->get('whitewashing.blog.blogservice');
        $blog = $blogRepository->createBlog($input->getArgument('name'));
        
        $output->writeln("Successfully created a new blog named '<info>". $blog->getName() . "</info>'.");
    }
}