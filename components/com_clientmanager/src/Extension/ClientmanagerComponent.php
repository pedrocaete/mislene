<?php
/**
 * @package     Cm\Component\Clientmanager
 *
 * @copyright   Copyright (C) 2025 Pedro Inácio Rodrigues Pontes. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Cm\Component\Clientmanager\Site\Extension;

use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;

\defined('_JEXEC') or die;

class ClientmanagerComponent extends MVCComponent
{
    use HTMLRegistryAwareTrait;
}
