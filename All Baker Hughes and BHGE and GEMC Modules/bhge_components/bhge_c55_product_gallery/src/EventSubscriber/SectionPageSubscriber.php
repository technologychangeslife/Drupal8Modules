<?php

namespace Drupal\bhge_c55_product_gallery\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Event subscriber for Product section page.
 */
class SectionPageSubscriber implements EventSubscriberInterface {

  /**
   * Code that should be triggered on event specified.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *
   *   Response event.
   */
  public function checkForRedirection(GetResponseEvent $event) {
    $node = $event->getRequest()->attributes->get('node');
    if (!empty($node->type->target_id) && $node->type->target_id == 'section') {
      $has_page = $node->field_has_page->value;
      $is_anonymous = \Drupal::currentUser()->isAnonymous();
      if (!$has_page && $is_anonymous) {
        if ($node->hasField('field_section_parents') && $node->field_section_parents->count() > 0) {
          $response = new RedirectResponse($node->field_section_parents[0]->entity->url(), 302);
        }
        else {
          $response = new RedirectResponse('/', 302);
        }
        $response->send();
      }
    }
  }

  /**
   * Getting SubscribedEvents.
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkForRedirection'];
    return $events;
  }

}
