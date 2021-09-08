<div class="riders-container home-favorite-riders">
    <h2 class="text-center mb-4">Top Riders</h2>
    <div class="home-riders">
        {foreach $riders as $riderData}
            <div class="rider pos-{$riderData@iteration}">
                <div class="image-container" style="background-image:url('{$riderData.thumb}');">
                    <div class="badge">
                        <div class="votes">{$riderData.rider->getVotes()}</div>
                        <div>votes</div>
                    </div>
                </div>
                <div class="rider-info">
                    <h3 class="rirder-name">{$riderData.rider->getName()}</h3>
                    <div class="rirder-discipline">{$riderData.rider->getDiscipline()}</div>
                </div>
            </div>
        {/foreach}
    </div>
</div>