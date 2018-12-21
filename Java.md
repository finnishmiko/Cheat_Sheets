# Test Java jsp-file with Docker

## Create a folder structure:

```
.
└───webapp
    └───WEB-INF
        ├───classes
        └───lib
```

- If there are jar-files, put them to `lib` folder. (F.ex. `commons-codec-1.11.jar`)
- Create `web.xml` to `WEB-INF` folder:

```xml
<web-app>
</web-app>
```

- And create a `index.jsp` file:

```jsp
<!doctype html>
<h1>It works!</h1>
<%
  for (int i = 0; i < 5; ++i) {
      out.println("<p>Hello, world!</p>");
  }
%>
```

- `Dockerfile` to the root folder of the project

```Docker
# docker build -t myapp .
# docker run --rm -it -p 8888:8080 myapp
# http://localhost:8888/webapp

FROM tomcat:9.0.1-jre8-alpine
ADD ./webapp /usr/local/tomcat/webapps/webapp
CMD ["catalina.sh", "run"]
```
