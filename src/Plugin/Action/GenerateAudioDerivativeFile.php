<?php

namespace Drupal\islandora_oral_history\Plugin\Action;

use Drupal\Core\Form\FormStateInterface;
use Drupal\islandora\Plugin\Action\AbstractGenerateDerivativeMediaFile;

/**
 * Emits a Node for generating audio derivatives file event.
 *
 * @Action(
 *   id = "generate_audio_derivative_file",
 *   label = @Translation("Generate an audio derivative as a file."),
 *   type = "media"
 * )
 */
class GenerateAudioDerivativeFile extends AbstractGenerateDerivativeMediaFile {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    $config['path'] = '[date:custom:Y]-[date:custom:m]/[media:mid]-Audio.mp3';
    $config['mimetype'] = 'audio/mpeg';
    $config['queue'] = 'islandora-connector-homarus';
    $config['destination_media_type'] = 'audio';
    $config['args'] = '-codec:a libmp3lame -q:a 5';
    $config['scheme'] = file_default_scheme();
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['mimetype']['#description'] = t('Mimetype to convert to (e.g. audio/mpeg, audio/m4a, etc...)');
    $form['args']['#description'] = t('Additional command line parameters for FFMpeg');
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::validateConfigurationForm($form, $form_state);
    $exploded_mime = explode('/', $form_state->getValue('mimetype'));
    if ($exploded_mime[0] != 'audio') {
      $form_state->setErrorByName(
        'mimetype',
        t('Please enter a audio mimetype (e.g. audio/mpeg, audio/m4a, etc...)')
      );
    }
  }

}
