<template>
    <div>
        <div class="entete">
            <h1>Inscription à l'arbre de noël</h1>
            <p>
                Pour Noël vous serez tous gâtés… ​
                …Enfin ceux qui veulent participer !!! ​
            </p>
            ​<p>
                Cette année Happitime organise un arbre de Noël Additi.​<br/>
                Serez-vous nombreux à vous prendre au jeu de la recherche des hobbies ​<br/>
                et des goûts de vos collègues pour leur offrir un cadeau personnalisé qui leur fera plaisir ?​
            </p>
 ​           <p>
                    Règles du jeu : ​<br/>
                1 – Inscription ci dessous avant le mardi 30 novembre 2021 ​<br/>
                2 – Découvrez le nom de votre collègue à qui vous allez offrir un cadeau à partir du 1er décembre 2021​<br/>
                3 - Trouvez LE cadeau personnalisé (budget maximum : 10€) ​<br/>
                4 – Déposez vos cadeaux dans la hotte du père Noël avant le 16 décembre 2021 ​<br/>
                5 – Rendez vous le vendredi 17 décembre à 10h00 dans le sapin​<br/>
            </p>

        </div>

        <Inscription />
    </div>
</template>

<script>

import Inscription from '~/components/Noel/Inscription.vue';

export default {
    name: 'PageInscription',
    layout: 'default',
    components: {
        Inscription,
    },
};
</script>


<style scoped>
.entete {
    background: #2a4a59;
    color: #fff;
    padding: 3rem 0;
}
</style>

