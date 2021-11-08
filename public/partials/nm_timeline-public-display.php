<?php

/**
 * Provide a public-facing view for the plugin
 */
ob_start(); ?>

<div class="nm-timeline">
  <div class="nmt-side">

    <div class="nmt-side-first-date nmt-date" 
         data-date="<?php echo $events[0]['date']; ?>" 
         data-id="<?php echo $events[0]['id']; ?>"
    >  
      <?php echo $events[0]['date'] ?>
    </div>

    <div class="nmt-side-mid-dates-wrapper">
      <div class="nmt-side-mid-dates">
        <?php for ($i = 1; $i < count($events) - 1; $i++): ?>
          <div class="nmt-side-mid-date nmt-date"
               data-date="<?php echo $events[$i]['date']; ?>"
               data-id="<?php echo $events[$i]['id']; ?>"
          >
            <?php echo $events[$i]['date']; ?>
          </div>
        <?php endfor; ?>
      </div>
      <div class="nmt-side-mid-separator">
        <div class="nmt-side-slider">
          <div class="nmt-side-slider-point"></div>
          <div class="nmt-side-slider-hand"></div>
          <div class="nmt-side-slider-label"></div>
        </div>
      </div>
    </div>    

    <div class="nmt-side-last-date nmt-date" 
         data-date="<?php echo $events[count($events) - 1]['date']; ?>" 
         data-id="<?php echo $events[count($events) - 1]['id']; ?>"
    >
    <?php echo $events[count($events) - 1]['date'] ?>
    </div>
  </div>
</div>


<?php
return ob_get_clean();
