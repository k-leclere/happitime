<!-- Please remove this file from your project -->
<template>
  <div class="liste">
    <ul>
      <li v-for="game in games">{{ game.nom }} {{ game.prenom }} {{ (game.jeux ? 'apporte des jeux': 'n\'a pas de jeux') }} {{ (game.master ? 'est game master': 'pas game master') }}</li>
    </ul>
  </div>
</template>

<script>

export default {
    name: 'Inscription',
    data() {
        return {
          games: [],
        };
    },
    async mounted() {
      let { data: games, error } = await supabase
        .from('games')
        .select('*')

      this.games = games;
    }
};
</script>


<style scoped>
.inscription {
  padding: 3rem 0;
}

span {
  color: #e2001a;
}

input,select {
  display: block;
  margin: 7px auto;
  width: 320px;
  padding: 5px 0;
  text-align: center;
}

input[type=submit] {
  font-size: 14px;
  font-weight: 600;
  height: 34px;
  background: #e2001a;
  color: #fff;
  border: 0;
  padding: 0 25px;
  margin: 1.68em auto;
  border-radius: 3px;
  transition: all .4s;
  width: 120px;
}
.radio > * {
  display: inline-block;
  width: 30px;
}
section {
  padding: 10px 0;
}
</style>
