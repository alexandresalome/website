<?php echo $form->renderHiddenFields(); ?>
<p>
    <label for="<?php echo $form['name']->getId() ?>">Your name <span class="required">*</span> :</label>
    <?php echo $form['name']->render(); ?>
</p>
<p>
    <label for="<?php echo $form['email']->getId() ?>">Your email <span class="required">*</span> :</label>
    <?php echo $form['email']->render(); ?>
</p>
<p>
    <label for="<?php echo $form['subject']->getId() ?>">A subject :</label>
    <?php echo $form['subject']->render(); ?>
</p>
<p>
    <label for="<?php echo $form['body']->getId() ?>">A message <span class="required">*</span> :</label>
    <?php echo $form['body']->render(); ?>
</p>
<p class="submit">
    <input type="submit" value="Send the message" />
</p>
