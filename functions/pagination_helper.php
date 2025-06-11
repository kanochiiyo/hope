<?php

/**
 * Mengambil parameter pagination dari URL dan menghitung nilai-nilai terkait.
 *
 * @param string $pageParam Nama parameter GET untuk halaman (e.g., 'page_unconfirmed', 'page_items').
 * @param int $defaultLimit Jumlah item per halaman default.
 * @return array Berisi 'currentPage', 'limit', 'offset'.
 */
function getPaginationParams($pageParam, $defaultLimit = 10)
{
  $currentPage = isset($_GET[$pageParam]) ? (int) $_GET[$pageParam] : 1;
  $currentPage = max(1, $currentPage); // Pastikan halaman tidak kurang dari 1
  $offset = ($currentPage - 1) * $defaultLimit;

  return [
    'currentPage' => $currentPage,
    'limit' => $defaultLimit,
    'offset' => $offset
  ];
}

/**
 * Menghitung total halaman berdasarkan total baris dan limit per halaman.
 *
 * @param int $totalRows Total baris data.
 * @param int $limit Jumlah item per halaman.
 * @return int Total halaman.
 */
function calculateTotalPages($totalRows, $limit)
{
  if ($limit == 0)
    return 1; // Mencegah divisi oleh nol
  return ceil($totalRows / $limit);
}

/**
 * Fungsi untuk menghasilkan markup pagination HTML.
 *
 * @param int $currentPage Halaman saat ini.
 * @param int $totalPages Total halaman yang tersedia.
 * @param string $pageParam Nama parameter GET untuk halaman ini (misal: 'page_unconfirmed').
 * @param array $otherParams Parameter GET lain yang ingin dipertahankan di URL.
 * @param string $ariaLabel Label aksesibilitas untuk navigasi.
 * @return string HTML markup untuk pagination.
 */
function generatePaginationHtml($currentPage, $totalPages, $pageParam, $otherParams = [], $ariaLabel = 'Page navigation')
{
  $html = '<nav aria-label="' . htmlspecialchars($ariaLabel) . '">';
  $html .= '<ul class="pagination mb-0">';

  // Link Previous
  $prevPage = $currentPage - 1;
  $prevLink = '?' . http_build_query(array_merge($otherParams, [$pageParam => $prevPage]));
  $html .= '<li class="page-item mx-1 ' . ($currentPage <= 1 ? 'disabled' : '') . '">';
  $html .= '<a class="page-link" href="' . ($currentPage <= 1 ? '#' : htmlspecialchars($prevLink)) . '" aria-label="Previous">&lt;</a>';
  $html .= '</li>';

  // Page Numbers
  for ($i = 1; $i <= $totalPages; $i++) {
    $pageLink = '?' . http_build_query(array_merge($otherParams, [$pageParam => $i]));
    $html .= '<li class="page-item mx-1 ' . ($i == $currentPage ? 'active' : '') . '">';
    $html .= '<a class="page-link" href="' . htmlspecialchars($pageLink) . '">' . $i . '</a>';
    $html .= '</li>';
  }

  // Link Next
  $nextPage = $currentPage + 1;
  $nextLink = '?' . http_build_query(array_merge($otherParams, [$pageParam => $nextPage]));
  $html .= '<li class="page-item mx-1 ' . ($currentPage >= $totalPages ? 'disabled' : '') . '">';
  $html .= '<a class="page-link" href="' . ($currentPage >= $totalPages ? '#' : htmlspecialchars($nextLink)) . '" aria-label="Next">&gt;</a>';
  $html .= '</li>';

  $html .= '</ul>';
  $html .= '</nav>';

  return $html;
}

?>