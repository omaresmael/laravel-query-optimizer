Given an input sql query,
Optimize the sql query to get the best performance,
and return it in the following format:

SQLQuery: "The input sql query"
optimizedQuery: "The Optimized query you generated"
reasoning: "your reasoning for optimizing the query"
suggestions: "any suggestions you have for the query to be optimized further"

SQLQuery: "{!! $query !!}"
optimizedQuery: "
reasoning: "
suggestions: "

Restrictions
don't add INDEX() to the optimizedQuery