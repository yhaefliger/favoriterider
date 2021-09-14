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
 <div class="riders-widget">
  <div class="riders-thumbs" id="ridersThumb" data-init="{$current}">
    {foreach $riders as $rider}
      <div class="rider-thumb pos-{$rider.position} {if $current == $rider@index}current{/if} {if $rider.id == $voted_id}voted{/if}" id="riderThumb-{$rider@index}">
        <button class="rider-thumb-btn" data-index="{$rider@index}" aria-label="Show {$rider.name}">
          <img src="{$rider.image.md}" class="thumb" alt="{$rider.name} Thumb" width="90" height="90" />
          <div class="rider-thumb-info">
            <div class="votes">
              {if $rider.id == $voted_id}
                <i class="material-icons">favorite</i>
              {else}
                <i class="material-icons">favorite_border</i>
              {/if}
              {$rider.votes}
            </div>
            <div class="rider-short-name">
              {$rider.short_name}
            </div>
            
          </div>
          
        </button>
      </div>
    {/foreach}
  </div>
  
  <div class="riders-carousel" id="ridersCarousel" style="display:none;">
    {foreach $riders as $rider}
      <div class="relative rider-carousel-item">
        <img src="{$rider.image.xl}" class="img-fluid" />
        <div class="rider-info">
          <div class="wrapper">
            <h3>{$rider.name}</h3>
            <div class="rider-discipline">{$rider.discipline}</div>
            <div class="alert alert-info rider-votes-alert" role="alert">
              {l s='This rider is ranked <strong>#%position%</strong> with <strong>%votes%</strong> votes recieved' sprintf=['%position%' => $rider.position, '%votes%' => $rider.votes] d='Modules.Favoriterider.Shop'}
            </div>
            <div class="rider-vote-form">
              {if $rider.id == $voted_id}
                <div class="rider-voted">
                  <i class="material-icons">favorite</i>
                  {l s='You have voted for this rider.<br />Thanks for participating!' d='Modules.Favoriterider.Shop'}
                </div>
              {else}
                <form method="POST" action="{$vote_url nofilter}">
                  <input type="hidden" name="id_rider" value="{$rider.id}" />
                  <button type="submit" class="btn btn-big btn-vote">
                    {l s='Vote for this rider' d='Modules.Favoriterider.Shop'}
                  </button>
                  {if $voted_id > 0}
                    <div class="text-muted mt-1">
                      {l s='You have already voted. If you vote for this rider, your current vote for %previous_rider% will be removed.' sprintf=['%previous_rider%' => $voted.name] d='Module.Favoriterider.Shop'}
                    </div>
                  {/if}
                </form>
                
              {/if}
            </div>
          </div>
        </div>
      </div>
    {/foreach}
  </div>
 </div>