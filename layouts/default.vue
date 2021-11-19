<template>
    <div id="site-wrapper">
        <header>
            <img width="150" height="72" class='logo-happitime' src='/logo/happitime.png' />
            <img width="150" height="55" class='logo-additi' src='/logo/additi.png' />
        </header>
        <nuxt />
    </div>
</template>

<script>

export default {};
</script>


<style>
@import url('https://fonts.googleapis.com/css2?family=Fira+Sans:wght@100;200;300;400;500;600;800&family=Open+Sans:ital,wght@0,300;0,400;0,600;1,300&display=swap');

header {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
}

body {
    font-family: 'Fira Sans',sans-serif;
    text-align: center;
    background: #eaeaea;
}
</style>