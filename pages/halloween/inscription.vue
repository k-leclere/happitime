<template>
    <div>
        <div class="entete">
            <h1>Inscription Halloween Party</h1>
            ​<p>
                Venez participer à nos événements Halloween !
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

