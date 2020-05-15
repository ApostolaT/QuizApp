# QuizApp
This quiz application is intended to provide a platform for simple quiz tests.
The flow is devided in two: Candinate and Adminastrator.

An administrator can:
1. CRUD on Quiz Questions
2. CRUD on Quizzes.
3. CRUD on Candidates.
4. Review Candidates' quizzes and give them a mark, based on their performance.

A candidate can take quizzes after he logs in.

This projects uses 3 frameworks developed by myself:
1. ReallyORM - a custom ORM for connection to database.
2. MVC - a custom MVC for HTTP request handling and FE rendering.
3. codeHighLightLib - a library that detects the candidate's asnwers which contain
code and highlights the key words, variables, numbers, and php function names.
