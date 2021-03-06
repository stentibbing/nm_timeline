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
          <div class="nmt-side-slider-label"><?php echo $events[0]['date']; ?></div>
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
  
  <div class="nmt-content">
    <?php for ($i = 0; $i <= count($events) - 1; $i++): ?>
      <div class="nmt-content-event<?php echo $i == 0 ? ' active-event' : ''; ?>" data-id="<?php echo $events[$i]['id']; ?>">
        <div class="nm-content-event-wrapper">
          <div class="nmt-content-side">
            <h2 class="nmt-content-title"><?php echo $events[$i]['title']; ?></h2>
            <div class="nmt-content-excerpt"><p><?php echo $events[$i]['excerpt']; ?></p></div>
          </div>
          <div class="nmt-content-img">
            <?php echo get_the_post_thumbnail($events[$i]['id']); ?>
          </div>
        </div>
      </div>
    <?php endfor; ?>
  </div>
</div>

<div class="nm-timeline-mobile">
  <?php foreach ($events as $event): ?>
    <div class="nmt-mobile-event">
      <div class="nmt-mobile-event-heading">
        <div class="nmt-mobile-event-date"><?php echo $event['date']; ?></div>
        <h2><?php echo $event['title']; ?></h2>
      </div>
      <div class="nmt-mobile-event-content">        
        <div class="nmt-mobile-event-img">
          <?php echo get_the_post_thumbnail($event['id']); ?>
        </div>
        <p class="nmt-mobile-event-text">
          <?php echo $event['excerpt']; ?>
        </p>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php
return ob_get_clean();
