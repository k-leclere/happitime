<template>
    <div>
        <div class="entete">
            <h1>Inscription à l'arbre de noël</h1>
            <p>
                Pour Noël vous serez tous gâtés… ​
                …Enfin ceux qui veulent participer !!! ​
            </p>
            ​<p>
                Cette année encore, Happitime organise un arbre de Noël Additi *​
            </p>
            <p>
                Serez-vous nombreux à vous prendre au jeu de la recherche des hobbies ​<br/>
                et des goûts de vos collègues pour leur offrir un cadeau personnalisé qui leur fera plaisir ?​
            </p>
 ​           <p>
                    Règles du jeu : ​<br/>
                1 – Inscription ci-dessous jusqu'au <b>mercredi 30 novembre 2022</b>​<br/>
                2 – Le <b>1er décembre 2022</b>, découvrez le nom du collègue à qui vous allez offrir un cadeau (tirage au sort**)<br/>
                3 - Trouvez <b>LE</b> cadeau personnalisé (budget maximum : 10€) <br/>
                4 – Déposez vos cadeaux dans la hotte du père Noël les <b>14 et 15 décembre 2022</b><br/>
                5 – Rendez-vous le <b>vendredi 16 décembre à 10h00</b> pour l'ouverture des cadeaux<br/>
            </p>
            <em>
                *Que vous soyez au siège ou en agence, vous êtes tous conviés.<br/>
                **Tirage au sort organisé par agence ou en BU voisines au siège Rennais.<br/> 
                Vous pourrez offrir un cadeau à un collègue de votre BU ou à un collègue de la BU juste à côté
            </em>
            <p>
                <br/><br/>
                <b>Les inscriptions sont désormais closes. Rendez-vous l'année prochaine ;-)</b>
            </p>
        </div>

        <!-- <Inscription /> -->
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

em {
    font-size: 0.7rem;
}
</style>

