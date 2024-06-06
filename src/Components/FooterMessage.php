<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FooterMessage
{
    public string $message;

    private array $availableMessages = [
        "Ne lâche rien !",
        "Tu es sur la bonne voie !",
        "N'oublie pas de rester bien hydraté.",
        "N'oublie pas que lorsque tu auras terminé 80% du test, il te restera encore les 80% restants.",
        "Tu vas y arriver !",
        "Le copier-coller a été programmé par des programmeurs pour des programmeurs.",
        "Dans le monde il y a 10 types de personnes, ceux qui comprennent le binaire, et les autres.",
        "Théoriquement, ça marche.",
        "Règle N° 1 : Si tout va bien, ne toucher à rien.",
        "Le chemin se fait en marchant !",
        "Ce n'est pas un bug, c'est une fonctionnalité.",
        "90% du temps la source du bug se situe entre le clavier et la chaise.",
        "En théorie, il n'y a pas de différence entre la théorie et la pratique.",
        "L'Informatique est une affaire trop sérieuse pour la confier à des informaticiens.",
        "Dans le doute reboot !",
        "Ce coup-ci, ça va fonctionner.",
        "Tant qu'il y a de la RAM, y'a de l'espoir.",
        "Le vrai geek est celui qui croit qu'il y a 1024 mètres dans un kilomètre."
    ];

    public function mount(bool $isSuccess = true)
    {
        $this->message = $this->availableMessages[random_int(0, 16)];
    }
}