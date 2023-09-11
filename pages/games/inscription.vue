<template>
    <div>
        <div class="entete">
            <h1>Inscription au 1er Additi Games</h1>
            <p>
                Le 28 septembre 2023, Happitime organise les 2nd Additi Games de 18h à 20h.
            </p>
            <p>
                L'objectif est de s'amuser autour de jeux de société, 
                plusieurs tables et game masters vous accueilleront afin de vous faire partager leur passion du jeu.
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
            nbMax: 67,
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

