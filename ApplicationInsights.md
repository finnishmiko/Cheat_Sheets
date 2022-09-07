# Application Insights

Query example with time filtering.

```s
union isfuzzy=true
    requests,
    traces,
    exceptions,
    customEvents
| where timestamp > datetime("2022-05-24T10:00:00.000Z") and timestamp < datetime("2022-05-24T12:00:00.000Z")
| order by timestamp desc
| take 100
```

Example snippets

```s
where timestamp > ago(10h)
```
