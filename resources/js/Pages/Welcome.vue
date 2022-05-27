<template>
    <div v-for="quiz in quizzes">
        <p>{{ quiz.title }} <a :href="/quiz/+ quiz.id">Take this quiz {{quiz.id}}</a></p>
    </div>
</template>
<script>
export default {
    props: {
        quizzes: Array
    }
}
</script>

<style scoped>

</style>
