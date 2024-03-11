<template>
    <div>
        <div class="entete">
            <h1>Inscription au 3ème Additi Games</h1>
            <p>
                Retrouvez Happitime le jeudi 21 mars 2024 à l'accueil d'Additi de 18h à 20h. 
            </p>
            <p>
                L'objectif est de s'amuser et de partager un moment convivial autour de jeux de société. 
            </p>
            <p>
                Plusieurs tables de jeu seront mises à disposition et des games masters seront là pour vous accueillir.
            </p>
            ​<p>
                Attention les places sont limitées à {{nbMax}} participants. 
            </p>
        </div>

        <inscription :nb-max="nbMax"/>
    </div>
</template>

<script>

import Inscription from '~/components/Games/Inscription.vue';

export default {
    name: 'PageInscription',
    layout: 'default',
    components: {
        Inscription,
    },
    data() {
        return {
            nbMax: 40,
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

