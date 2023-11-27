<template>
    <div>
        <div class="entete">
            <h1>Animations Arbre de noël</h1>
            <p>
                Happitime vous propose des ateliers déco et un film de noël. 
            </p>
        </div>

        <Ateliers />
    </div>
</template>

<script>

import Ateliers from '~/components/Noel/Ateliers.vue';

export default {
    name: 'PageAteliers',
    layout: 'default',
    components: {
        Ateliers,
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

