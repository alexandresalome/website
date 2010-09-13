<p><strong><?php echo $contact->getName(); ?>,</strong></p>

<p>Your message was successfully sent, and soon will be read !</p>

<p><strong>Subject</strong> : <?php echo $message->getSubject() ?></p>

<p><?php echo nl2br($message->getBody()); ?></p>
