<?php

namespace AffiliateX\Platforms;

use AffiliateX\Platforms\Admin\PlatformSettings;

defined( 'ABSPATH' ) || exit;

/**
 * This controller is responsible to initialize all Amazon classes and methods
 *
 * @package AffiliateX
 */
class PlatformController {
	public function __construct() {
		new PlatformSettings();
	}
}
