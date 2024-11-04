<?php

declare(strict_types=1);

namespace App\Error;

use Cake\Error\ExceptionRendererInterface;
use Cake\Http\Response;
use Cake\Log\Log;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiExceptionRenderer implements ExceptionRendererInterface
{
  protected Throwable $exception;

  public function __construct(Throwable $exception)
  {
    $this->exception = $exception;
  }

  public function render(): ResponseInterface|string
  {
    $code = $this->normalizeStatusCode();
    $error = $this->createProblemDetails();

    try {
      return new Response([
        'type' => 'application/problem+json',
        'body' => json_encode($error, JSON_THROW_ON_ERROR),
        'status' => $code
      ]);
    } catch (\JsonException $e) {
      return new Response([
        'type' => 'application/problem+json',
        'body' => json_encode([
          'type' => 'about:blank',
          'title' => 'Internal Server Error',
          'status' => 500,
          'detail' => 'An internal error occurred while processing your request.',
        ]),
        'status' => 500
      ]);
    }

    Log::error('Unexpected Error Cccurred', $error);
  }

  protected function createProblemDetails(): array
  {
    $code = $this->normalizeStatusCode();
    $title = $this->getErrorTitle($code);

    return [
      'title' => $title,
      'status' => $code,
      'detail' => $this->exception->getMessage() ?: $title,
      'additional_info' => [
        'exception_type' => get_class($this->exception),
        'file' => $this->exception->getFile(),
        'line' => $this->exception->getLine(),
      ]
    ];
  }

  protected function normalizeStatusCode(): int
  {
    $code = $this->exception->getCode();
    if ($code < 100 || $code > 599) {
      return 500;
    }
    return $code ?: 500;
  }

  protected function getErrorTitle(int $code): string
  {
    return match ($code) {
      400 => 'Bad Request',
      401 => 'Unauthorized',
      403 => 'Forbidden',
      404 => 'Not Found',
      409 => 'Conflict',
      422 => 'Unprocessable Entity',
      500 => 'Internal Server Error',
      default => 'Unknown Error'
    };
  }

  public function write(ResponseInterface|string $output): void
  {
    if ($output instanceof ResponseInterface) {
      echo $output->getBody();
    } else {
      echo $output;
    }
  }
}
