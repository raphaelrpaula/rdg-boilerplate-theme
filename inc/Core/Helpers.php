<?php

namespace RDG\Core;

class Helpers
{
  public static function get_section_title($hat, $title, $subtitle = ""): string
  {
    if (empty($title)) return '';
    ob_start(); ?>

    <div class="section-title">
      <?php if ($hat) { ?>
        <span class="hat"><?= esc_html($hat); ?></span>
      <?php } ?>
      <h2><?= $title; ?></h2>
      <?php if ($subtitle) { ?>
        <p class="subtitle"><?= esc_html($subtitle); ?></p>
      <?php } ?>
    </div>

<?php return ob_get_clean();
  }

  /**
   * Retorna as redes sociais ativas configuradas no Customizer.
   * @return array Lista de redes sociais filtradas.
   */
  public static function get_social(): array
  {
    static $social_data = null;
    if ($social_data !== null) {
      return $social_data;
    }

    $config_path = get_template_directory() . '/theme-config.json';

    // Proteção 1: Arquivo não existe? Retorna vazio sem quebrar o site
    if (!file_exists($config_path)) {
      return [];
    }

    $config = json_decode(file_get_contents($config_path), true);

    // Proteção 2: JSON inválido ou chave ausente? Retorna vazio
    if (empty($config['social_networks']) || !is_array($config['social_networks'])) {
      return [];
    }

    $social_data = [];

    foreach ($config['social_networks'] as $slug => $data) {
      // Garante que as chaves internas existem antes de usá-las
      $mod_key = $data['mod'] ?? '';
      $icon    = $data['icon'] ?? '';

      if (empty($mod_key)) continue;

      $link = get_theme_mod($mod_key);

      if (!empty($link)) {
        $social_data[$slug] = [
          'icon' => $icon,
          'link' => esc_url($link)
        ];
      }
    }

    return $social_data;
  }

  /**
   * Limpa números de telefone, mantendo apenas dígitos.
   * @param string|null $number O número a ser limpo.
   * @return string Retorna apenas os números ou string vazia.
   */
  public static function clear_phone(?string $number): string
  {
    if (!$number) {
      return '';
    }

    $clean = preg_replace('/\D/', '', $number);

    return (string) $clean;
  }

  /**
   * Constrói o conteúdo interno de botões e links (Texto + Ícone)
   * Método privado para evitar repetição de código.
   */
  private static function build_inner_content(string $label, string $icon, string $icon_pos, string $icon_type = 'class'): string
  {
    $html_label = '<span>' . esc_html($label) . '</span>';
    $html_icon  = '';

    if (!empty($icon)) {
      if ($icon_type === 'svg') {
        $html_icon = '<span class="icon-svg">' . self::get_svg($icon) . '</span>';
      } else {
        $html_icon = '<i class="' . esc_attr($icon) . '"></i>';
      }
    }

    if (empty($icon)) {
      return $html_label;
    }

    return ($icon_pos === 'left') ? $html_icon . ' ' . $html_label : $html_label . ' ' . $html_icon;
  }

  /**
   * Engine de SVG: Lê e retorna o conteúdo de um arquivo .svg da pasta de ícones
   * @param string $icon_name Nome do arquivo (sem o .svg)
   * @return string O código HTML/SVG
   */
  public static function get_svg(string $icon_name): string
  {
    // Cache em memória para não ler o mesmo arquivo várias vezes na página
    static $svg_cache = [];

    if (isset($svg_cache[$icon_name])) {
      return $svg_cache[$icon_name];
    }

    $file_path = get_template_directory() . '/template-parts/icons/' . sanitize_file_name($icon_name) . '.svg';

    if (file_exists($file_path)) {
      // Lê o arquivo e guarda no cache
      $svg_cache[$icon_name] = file_get_contents($file_path);
      return $svg_cache[$icon_name];
    }

    return ''; // Retorna vazio se o ícone não existir (não quebra o site)
  }

  /**
   * Renderiza um Botão padronizado (Navegação ou Ação)
   * * @param string $label O texto do botão
   * @param string $url O link de destino (deixe vazio se for button_type = action)
   * @param array $args Argumentos extras de configuração
   */
  public static function get_button(string $label, string $url = '', array $args = []): string
  {
    if (empty($label)) return '';

    $defaults = [
      'style'       => 'primary',
      'icon'        => '',
      'icon_pos'    => 'right',
      'icon_type'   => 'class',
      'button_type' => 'navigation',
      'target'      => '_self',
      'class'       => '',
      'attributes'  => '' // Ex: 'data-popup="abrir" aria-expanded="false"'
    ];

    $args = wp_parse_args($args, $defaults);

    // Monta as classes do botão
    $btn_classes = array_filter([
      'btn',
      'btn-' . $args['style'],
      $args['icon'] ? 'btn-icon' : '',
      $args['class']
    ]);

    $inner_content = self::build_inner_content($label, $args['icon'], $args['icon_pos'], $args['icon_type']);

    // Montagem Condicional da Tag e Atributos
    if ($args['button_type'] === 'action') {
      $tag = 'button';
      $html_attributes = sprintf(
        'type="button" class="%s" %s',
        esc_attr(implode(' ', $btn_classes)),
        $args['attributes']
      );
    } else {
      $tag = 'a';
      $url_attr = !empty($url) ? 'href="' . esc_url($url) . '"' : '';
      $html_attributes = sprintf(
        '%s class="%s" target="%s" role="button" %s',
        $url_attr,
        esc_attr(implode(' ', $btn_classes)),
        esc_attr($args['target']),
        $args['attributes']
      );
    }

    return sprintf(
      '<%1$s %2$s>%3$s</%1$s>',
      $tag,
      trim($html_attributes),
      $inner_content
    );
  }

  /**
   * Renderiza um Link padronizado
   * * @param string $label O texto do link
   * @param string $url O link de destino
   * @param array $args Argumentos extras: icon, icon_pos (left|right), target, class
   */
  public static function get_link(string $label, string $url, array $args = []): string
  {
    if (empty($label) || empty($url)) return '';

    // Valores padrão
    $defaults = [
      'icon'     => '',
      'icon_pos' => 'right',
      'icon_type' => 'class',
      'target'   => '_self',
      'class'    => '',
    ];

    $args = wp_parse_args($args, $defaults);

    // Monta as classes do link
    $link_classes = array_filter([
      'link',
      $args['icon'] ? 'link-icon' : '',
      $args['class']
    ]);

    $inner_content = self::build_inner_content($label, $args['icon'], $args['icon_pos'], $args['icon_type']);

    return sprintf(
      '<a href="%s" class="%s" target="%s">%s</a>',
      esc_url($url),
      esc_attr(implode(' ', $link_classes)),
      esc_attr($args['target']),
      $inner_content
    );
  }

  /**
   * Retorna as filiais da Frigelar, contidas no arquivo /assets/json/filiais.json.
   * @return array Lista de redes sociais filtradas.
   */
  public static function get_filiais(): array
  {
    static $filiais_data = null;
    if ($filiais_data !== null) {
      return $filiais_data;
    }

    $filiais_path = get_template_directory() . '/assets/json/filiais.json';

    if (!file_exists($filiais_path)) {
      return [];
    }

    $filiais = json_decode(file_get_contents($filiais_path), true);

    if (empty($filiais['filiais']) || !is_array($filiais['filiais'])) {
      return [];
    }

    $filiais_data = [];

    foreach ($filiais['filiais'] as $slug => $data) {
      $endereco = $data['endereco'] ?? '';
      $estado    = $data['estado'] ?? '';

      if (!empty($endereco)) {
        $filiais_data[$slug] = [
          'endereco' => ucwords(mb_strtolower($endereco)),
          'estado' => !empty($estado) ? ucwords(mb_strtolower($estado)) : '',
        ];
      }
    }

    return $filiais_data;
  }
}
