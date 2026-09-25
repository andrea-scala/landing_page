<?php
declare(strict_types=1);

require_once __DIR__ . '/auth-check.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Metodo non consentito.');
}

$csrfToken = (string) ($_POST['csrf_token'] ?? '');
$pageName  = (string) ($_POST['page'] ?? '');

if (!citytel_admin_csrf_validate($csrfToken)) {
    header('Location: index.php?status=error');
    exit;
}

if (!citytel_valid_page_name($pageName)) {
    header('Location: index.php?status=error');
    exit;
}

$filePath = CITYTEL_CONTENT_DIR . $pageName . '.json';

if (!is_readable($filePath)) {
    header('Location: index.php?status=error');
    exit;
}

$original = citytel_load_json_file($filePath);
$posted   = $_POST['fields'] ?? [];

if (!is_array($posted)) {
    $posted = [];
}

/**
 * Ricostruisce ricorsivamente la struttura dati partendo dall'originale
 * (usato come "schema": tipi e forma attesi) e dai valori inviati dal form.
 *
 * Se il valore inviato non rispetta la forma attesa (es. atteso array,
 * ricevuto scalare), si ripiega sul valore originale per non corrompere
 * il file — nessun dato malformato viene mai scritto su disco.
 *
 * Ogni stringa scalare viene sanificata con strip_tags() + trim() prima
 * di essere salvata: l'output verso il pubblico resta comunque protetto
 * da citytel_e()/htmlspecialchars() nelle viste, questo è un ulteriore
 * livello di difesa contro l'inserimento di markup nei contenuti.
 */
function citytel_admin_rebuild(mixed $original, mixed $posted): mixed
{
    if (is_array($original)) {
        if ($original === [] || !array_is_list($original)) {
            // Assoc (o array vuoto trattato come contenitore di chiavi note).
            $result = [];
            foreach ($original as $key => $childOriginal) {
                $childPosted = (is_array($posted) && array_key_exists($key, $posted))
                    ? $posted[$key]
                    : null;
                $result[$key] = citytel_admin_rebuild($childOriginal, $childPosted);
            }
            return $result;
        }

        $isScalarList = true;
        foreach ($original as $item) {
            if (!is_scalar($item) && $item !== null) {
                $isScalarList = false;
                break;
            }
        }

        if ($isScalarList) {
            if (!is_string($posted)) {
                return $original;
            }
            $lines = preg_split('/\r\n|\r|\n/', $posted) ?: [];
            $lines = array_map(static fn (string $l): string => trim(strip_tags($l)), $lines);
            $lines = array_values(array_filter($lines, static fn (string $l): bool => $l !== ''));
            return $lines;
        }

        // Lista di array (oggetti): ricostruisci elemento per elemento.
        $result = [];
        foreach ($original as $index => $childOriginal) {
            $childPosted = (is_array($posted) && array_key_exists($index, $posted))
                ? $posted[$index]
                : [];
            $result[] = citytel_admin_rebuild($childOriginal, $childPosted);
        }
        return $result;
    }

    // Scalare.
    if (is_array($posted)) {
        return $original;
    }

    return trim(strip_tags((string) ($posted ?? '')));
}

$rebuilt = [];
foreach ($original as $key => $value) {
    $childPosted = (is_array($posted) && array_key_exists($key, $posted)) ? $posted[$key] : null;
    $rebuilt[$key] = citytel_admin_rebuild($value, $childPosted);
}

$saved = citytel_write_json_file($filePath, $rebuilt);

header('Location: index.php?status=' . ($saved ? 'ok' : 'error') . '&page=' . urlencode($pageName));
exit;