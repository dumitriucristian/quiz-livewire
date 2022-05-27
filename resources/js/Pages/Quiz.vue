<template>
    <p>here should be a quiz></p>
    {{title}}
    <div v-for="question in questions">
        <p :key="question.id">{{question.text}}</p>
        <div v-for="answer in question.answers">
            <p :key="answer.id">answer </p>
        </div>
    </div>
</template>

<script>
export default {
    name: "Quiz.vue",
    props: {
        'title': String,
        'questions': Object
    }
}
</script>

<style scoped>

</style>
