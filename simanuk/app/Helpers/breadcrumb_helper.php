<?php

if (! function_exists('render_breadcrumb')) {
   /**
    * Merender HTML breadcrumb dari sebuah array.
    *
    * @param array $breadcrumbs Data breadcrumb yang dikirim dari controller.
    *
    * @return string HTML dari breadcrumb.
    */
   function render_breadcrumb(array $breadcrumbs = []): string
   {
      if (empty($breadcrumbs)) {
         return '';
      }

      $html = '<nav class="flex mb-4" aria-label="Breadcrumb">';
      $html .= '<ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">';

      foreach ($breadcrumbs as $index => $crumb) {
         $isLast = $index === array_key_last($breadcrumbs);

         $html .= '<li><div class="flex items-center">';

         if ($index > 0) {
            $html .= '<svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" /></svg>';
         }

         if (! $isLast && isset($crumb['url'])) {
            $html .= '<a href="' . esc($crumb['url'], 'attr') . '" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2">' . esc($crumb['name']) . '</a>';
         } else {
            $html .= '<span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">' . esc($crumb['name']) . '</span>';
         }

         $html .= '</div></li>';
      }

      $html .= '</ol></nav>';

      return $html;
   }
}
