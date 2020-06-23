<?php

namespace OrderBundle\Form;

use OrderBundle\Entity\OrderInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->setAction("https://preprod.gpgcheckout.com/Paiement_test/Validation_paiement.php")
            ->setMethod('POST')*/
            ->add('customerFirstName', TextType::class, array(
                'required' => true,
                'label' => 'Nom',
            ))
            ->add('customerLastName', TextType::class, array(
                'required' => true,
                'label' => 'Prenom',
            ))
            ->add('customerEmail', TextType::class, array(
                'required' => false,
                'label' => 'Email',
            ))
            ->add('customerPhone', NumberType::class, array(
                'required' => true,
                'label' => 'Téléphone',
            ))
            ->add('customerCity', TextType::class, array(
                'required' => true,
                'label' => 'Ville',
            ))
            ->add('customerAddress', TextType::class, array(
                'required' => true,
                'label' => 'Adresse',
            ))
            ->add('postalCode', NumberType::class, array(
                'required' => false,
                'label' => 'Code postal',
            ))
            ->add('promo', TextType::class, array(
                'required' => false,
                'label' => 'Promo Code'
            ))
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Méthode de paiement',
                'choices'  => [
                    // 'Paiement à la livraison' => 'Paiement à la livraison',
                    'Credit card' => 'Credit card',
                ],
            ])
            ->add('pays', ChoiceType::class, [
                'label' => 'Pays',
                'choices' => [
                    'Afghanistan','Afrique Du Sud','Åland, Îles','Albanie','Algérie','Allemagne','Andorre','Angola','Anguilla','Antarctique','Antigua-Et-Barbuda','Arabie Saoudite','Argentine','Arménie','Aruba','Australie','Autriche','Azerbaïdjan','Bahamas','Bahreïn','Bangladesh','Barbade','Bélarus','Belgique','Belize','Bénin','Bermudes','Bhoutan','Bolivie, L\'état Plurinational De','Bonaire, Saint-Eustache Et Saba','Bosnie-Herzégovine','Botswana','Bouvet, Île','Brésil','Brunei Darussalam','Bulgarie','Burkina Faso','Burundi','Caïmans, Îles','Cambodge','Cameroun','Canada','Cap-Vert','Centrafricaine, République','Chili','Chine','Christmas, Île','Chypre','Cocos (Keeling), Îles','Colombie','Comores','Congo','Congo, La République Démocratique Du','Cook, Îles','Corée, République De','Corée, République Populaire Démocratique De','Costa Rica','Côte D\'ivoire','Croatie','Cuba','Curaçao','Danemark','Djibouti','Dominicaine, République','Dominique','Égypte','El Salvador','Émirats Arabes Unis','Équateur','Érythrée','Espagne','Estonie','États-Unis','Éthiopie','Falkland, Îles (Malvinas)','Féroé, Îles','Fidji','Finlande','France','Gabon','Gambie','Géorgie','Géorgie Du Sud-Et-Les Îles Sandwich Du Sud','Ghana','Gibraltar','Grèce','Grenade','Groenland','Guadeloupe','Guam','Guatemala','Guernesey','Guinée','Guinée-Bissau','Guinée Équatoriale','Guyana','Guyane Française','Haïti','Heard-Et-Îles Macdonald, Île','Honduras','Hong Kong','Hongrie','Île De Man','Îles Mineures Éloignées Des États-Unis','Îles Vierges Britanniques','Îles Vierges Des États-Unis','Inde','Indonésie','Iran, République Islamique D\'','Iraq','Irlande','Islande','Israël','Italie','Jamaïque','Japon','Jersey','Jordanie','Kazakhstan','Kenya','Kirghizistan','Kiribati','Koweït','Lao, République Démocratique Populaire','Lesotho','Lettonie','Liban','Libéria','Libye','Liechtenstein','Lituanie','Luxembourg','Macao','Macédoine, L\'ex-République Yougoslave De','Madagascar','Malaisie','Malawi','Maldives','Mali','Malte','Mariannes Du Nord, Îles','Maroc','Marshall, Îles','Martinique','Maurice','Mauritanie','Mayotte','Mexique','Micronésie, États Fédérés De','Moldova, République De','Monaco','Mongolie','Monténégro','Montserrat','Mozambique','Myanmar','Namibie','Nauru','Népal','Nicaragua','Niger','Nigéria','Niué','Norfolk, Île','Norvège','Nouvelle-Calédonie','Nouvelle-Zélande','Océan Indien, Territoire Britannique De L\'','Oman','Ouganda','Ouzbékistan','Pakistan','Palaos','Palestinien Occupé, Territoire','Panama','Papouasie-Nouvelle-Guinée','Paraguay','Pays-Bas','Pérou','Philippines','Pitcairn','Pologne','Polynésie Française','Porto Rico','Portugal','Qatar','Réunion','Roumanie','Royaume-Uni','Russie, Fédération De','Rwanda','Sahara Occidental','Saint-Barthélemy','Sainte-Hélène, Ascension Et Tristan Da Cunha','Sainte-Lucie','Saint-Kitts-Et-Nevis','Saint-Marin','Saint-Martin(Partie Française)','Saint-Martin (Partie Néerlandaise)','Saint-Pierre-Et-Miquelon','Saint-Siège (État De La Cité Du Vatican)','Saint-Vincent-Et-Les Grenadines','Salomon, Îles','Samoa','Samoa Américaines','Sao Tomé-Et-Principe','Sénégal','Serbie','Seychelles','Sierra Leone','Singapour','Slovaquie','Slovénie','Somalie','Soudan','Soudan Du Sud','Sri Lanka','Suède','Suisse','Suriname','Svalbard Et Île Jan Mayen','Swaziland','Syrienne, République Arabe','Tadjikistan','Taïwan, Province De Chine','Tanzanie, République-Unie De','Tchad','Tchèque, République','Terres Australes Françaises','Thaïlande','Timor-Leste','Togo','Tokelau','Tonga','Trinité-Et-Tobago','Tunisia','Turkménistan','Turks-Et-Caïcos, Îles','Turquie','Tuvalu','Ukraine','Uruguay','Vanuatu','Venezuela, République Bolivarienne Du','Viet Nam','Wallis Et Futuna','Yémen','Zambie','Zimbabwe',
                ],
                'choice_label' => function ($value) {
                    return $value;
                },
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'CREDIT CARDS',
                'attr' => array(
                    'class' => 'btn karl-checkout-btn'
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => OrderInfo::class,
        ));
    }
}