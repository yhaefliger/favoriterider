{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
<div class="riders-container home-favorite-riders">
    <h2 class="text-center mb-4">Top Riders</h2>
    <div class="home-riders">
        {foreach $riders as $rider}
            <div class="rider pos-{$rider@iteration}">
                <div class="image-container" style="background-image:url('{$rider.image}');">
                    <div class="badge">
                        <div class="votes">{$rider.votes}</div>
                        <div>votes</div>
                    </div>
                </div>
                <div class="rider-info">
                    <h3 class="rirder-name">{$rider.name}</h3>
                    <div class="rirder-discipline">{$rider.discipline}</div>
                </div>
            </div>
        {/foreach}
    </div>
    {if $link}
    <div class="cta-link ">
        <a class="btn btn-primary" href="{$link}">Votez pour votre rider préféré!</a>
    </div>
    {/if}
</div>