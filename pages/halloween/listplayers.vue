<template>
    <div>
        <div class="entete">
            <h1>Liste des joueurs de la Killer Party</h1>
        </div>

        <list-players/>
    </div>
</template>

<script>

import ListPlayers from '~/components/Halloween/ListPlayers.vue';

export default {
    name: 'PageInscription',
    layout: 'default',
    components: {
        ListPlayers,
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

