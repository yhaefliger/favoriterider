<div class="riders-container home-favorite-riders">
    <h2 class="text-center mb-4">Top Riders</h2>
    <div class="home-riders">
        {foreach $riders as $rider}
            <div class="rider">
                {$rider->getName()} 
            </div>
        {/foreach}
    </div>
</div>