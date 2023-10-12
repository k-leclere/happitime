<template>
    <div>
        <div class="entete">
            <h1>Inscription au 1er Additi E-Games</h1>
            <p>
                Le jeudi 16 septembre 2023, Happitime organise les 1er Additi E-Games de 18h à 20h30.
            </p>
            <p>
                Venez affronter vos collègues dans un tournoi de FIFA ou Mario-Kart. D'autres jeux vidéos seront à votre disposition.
            </p>
            ​<p>
                Attention les places sont limitées à {{nbMax}} participants. 
            </p>
        </div>

        <inscription :nb-max="nbMax"/>
    </div>
</template>

<script>

import Inscription from '~/components/EGames/Inscription.vue';

export default {
    name: 'PageInscription',
    layout: 'default',
    components: {
        Inscription,
    },
    data() {
        return {
            nbMax: 52,
        };
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

