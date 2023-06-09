<?php

namespace YaangVu\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package YaangVu\Exceptions
 * @OA\Schema(
 *     schema="QueryException"
 * )
 */
class QueryException extends BaseException
{
    /**
     *
     * @OA\Property(
     *   property="message",
     *   oneOf={
     *     @OA\Schema(type="string"),
     *     @OA\Schema(
     *          type="array",
     *          @OA\Items({})
     *     ),
     *   }
     * ),
     * @OA\Property(
     *     property="code",
     *     type="integer",
     *     format="int64",
     *     default="400"
     * ),
     *
     */
    public function __construct(array|string $message, ?Exception $e = null)
    {
        parent::__construct($message, $e);
    }
}
