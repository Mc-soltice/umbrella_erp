<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Umbrella Industrial Services ERP API",
 *      description="Documentation API de l'ERP Umbrella",
 *      @OA\Contact(email="support@umbrella.com")
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Serveur local"
 * )
 *
 *  * @OA\Schema(
 *     schema="UserRequest",
 *     type="object",
 *     required={"first_name","last_name","location","email","password"},
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="location", type="string", example="Yaoundé"),
 *     @OA\Property(property="phone", type="string", example="699123456"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="password", type="string", example="secret123"),
 *     @OA\Property(property="password_confirmation", type="string", example="secret123")
 * )
 * 
 * @OA\Schema(
 *     schema="Agent",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="matricule", type="string", example="AGT12345"),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="location", type="string", example="Yaoundé"),
 *     @OA\Property(property="phone", type="string", example="699123456"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="role", type="string", example="agent"),
 *     @OA\Property(property="site", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Site A"),
 *         @OA\Property(property="location", type="string", example="Douala")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Candidature",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="first_name", type="string", example="Alice"),
 *     @OA\Property(property="last_name", type="string", example="Smith"),
 *     @OA\Property(property="location", type="string", example="Yaoundé"),
 *     @OA\Property(property="phone", type="string", example="699654321"),
 *     @OA\Property(property="email", type="string", example="alice.smith@example.com"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Site",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Site A"),
 *     @OA\Property(property="location", type="string", example="Douala"),
 *     @OA\Property(property="responsable_id", type="integer", example=2),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="AgentRequest",
 *     type="object",
 *     required={"first_name","last_name","location","email","site_id","password"},
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="location", type="string", example="Yaoundé"),
 *     @OA\Property(property="phone", type="string", example="699123456"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="site_id", type="integer", example=1),
 *     @OA\Property(property="password", type="string", example="secret123"),
 *     @OA\Property(property="password_confirmation", type="string", example="secret123")
 * )
 */
class SwaggerBase
{
    // Ce fichier contient uniquement les annotations Swagger
}
