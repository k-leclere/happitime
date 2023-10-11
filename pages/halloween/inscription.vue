<template>
    <div>
        <div class="entete">
            <h1>Inscription Halloween Party</h1>
            ​<p>
                Du lundi 23 au mardi 31 octobre,
            venez participer à nos différents
            événements Halloween !
            </p>
            ​<p>
            Des questions ? L'équipe Happitime
            est là pour vous répondre !
            </p>
        </div>

        <inscription :nb-max="nbMax"/>
    </div>
</template>

<script>

import Inscription from '~/components/Halloween/Inscription.vue';

export default {
    name: 'PageInscription',
    layout: 'default',
    components: {
        Inscription,
    },
    data() {
        return {
            nbMax: 0,
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

