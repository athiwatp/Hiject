<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Hiject\Core\ExternalLink\ExternalLinkManager;
use Hiject\ExternalLink\WebLinkProvider;
use Hiject\ExternalLink\AttachmentLinkProvider;
use Hiject\ExternalLink\FileLinkProvider;

/**
 * External Link Provider
 */
class ExternalLinkProvider implements ServiceProviderInterface
{
    /**
     * Register providers
     *
     * @access public
     * @param  \Pimple\Container $container
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['externalLinkManager'] = new ExternalLinkManager($container);
        $container['externalLinkManager']->register(new WebLinkProvider($container));
        $container['externalLinkManager']->register(new AttachmentLinkProvider($container));
        $container['externalLinkManager']->register(new FileLinkProvider($container));

        return $container;
    }
}
