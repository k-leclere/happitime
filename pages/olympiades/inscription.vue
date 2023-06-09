<template>
    <div>
        <div class="entete">
            <h1>Inscription aux Olympiades d'Additi</h1>
            <p>
                Le 29 juin 2023, auront lieu les Olympiades Additi.
            </p>
            <p>
                L'objectif est de s'amuser et de participer à de petites épreuves en équipe. <br/>
                De la réflexion, de la logique et un peu de muscle, composez votre équipe afin d'affronter d'autres équipes dans la bonne humeur.
            </p>
        </div>

        <inscription :nb-max="nbMax"/>
    </div>
</template>

<script>

import Inscription from '~/components/Olympiades/Inscription.vue';

export default {
    name: 'PageInscription',
    layout: 'default',
    components: {
        Inscription,
    },
    data() {
        return {
            nbMax: 21,
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

