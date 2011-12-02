<div class="row profile">
  <div class="span5 sidebar">
    <div class="well media-grid">
      <h1><?php echo "$student->first_name $student->last_name"; ?></h1>
      <a href="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>">
        <img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>?s=200" alt="<?php echo $student->first_name; ?>'s Gravatar" class="thumbnail" />
      </a>
      <h6>Menu</h6>
      <hr />
      <?php echo Html::anchor('#', "Add $student->first_name as a Friend", array('class' => 'btn large success')); ?>
      <?php echo Html::anchor('#', "Send $student->first_name a Message", array('class' => 'btn large info')); ?>
      <?php echo Html::anchor('students/edit/'.$student->id, 'Edit your Profile', array('class' => 'btn large')); ?>
      <?php echo Html::anchor('students/delete/'.$student->id, 'Delete your Profile', array('class' => 'btn large danger','onclick' => "return confirm('Are you sure?')")); ?>

    </div>
  </div>
  <div class="span10 main">
    <div class="well">
      <ul class="tabs" data-tabs="tabs">
        <li class="active"><a href="#overview">Overview</a></li>
        <li class=""><a href="#education">Eduction</a></li>
        <li class=""><a href="#work">Work</a></li>
        <li class=""><a href="#interests">Interests</a></li>
      </ul>

      <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="overview">
          <ul class="unstyled">
            <li><strong>Gender:</strong> <?php echo $student->gender; ?></li>
            <li><strong>In a Relationship with:</strong> <a href="#">Jane Doe</a></li>
          </ul>
          <strong>Bio:</strong>
          <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>

          <h4><?php echo $student->first_name ?> has 288 friends:</h4>
          <div class="media-grid">
            <?php for($i = 0; $i < 32; $i++) { ?>
              <a href="#"><img class="thumbnail" src="http://www.gravatar.com/avatar/<?php echo md5(strtolower($student->email)); ?>?s=40" /></a>
            <?php } ?>
          </div>
          <p class="right-aligned"><a href="#">View All</a></p>
        </div>
        <div class="tab-pane" id="education">
          <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
        </div>
        <div class="tab-pane" id="work">
          <p>Banksy do proident, brooklyn photo booth delectus sunt artisan sed organic exercitation eiusmod four loko. Quis tattooed iphone esse aliqua. Master cleanse vero fixie mcsweeney's. Ethical portland aute, irony food truck pitchfork lomo eu anim. Aesthetic blog DIY, ethical beard leggings tofu consequat whatever cardigan nostrud. Helvetica you probably haven't heard of them carles, marfa veniam occaecat lomo before they sold out in shoreditch scenester sustainable thundercats. Consectetur tofu craft beer, mollit brunch fap echo park pitchfork mustache dolor.</p>
        </div>
        <div class="tab-pane" id="interests">
          <p>Sunt qui biodiesel mollit officia, fanny pack put a bird on it thundercats seitan squid ad wolf bicycle rights blog. Et aute readymade farm-to-table carles 8-bit, nesciunt nulla etsy adipisicing organic ea. Master cleanse mollit high life, next level Austin nesciunt american apparel twee mustache adipisicing reprehenderit hoodie portland irony. Aliqua tofu quinoa +1 commodo eiusmod. High life williamsburg cupidatat twee homo leggings. Four loko vinyl DIY consectetur nisi, marfa retro keffiyeh vegan. Fanny pack viral retro consectetur gentrify fap.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo Html::anchor('students', 'Back'); ?>

<?php var_dump($student); ?>
