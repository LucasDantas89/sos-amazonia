<%@page language="java" import="java.sql.*" %>

<%
    //cria as variaveis e armazena as informações digitadas pelo usuário
    String vnome  = request.getParameter("register-name") ;
    String vemail = request.getParameter("register-email");
    String vsenha = request.getParameter("register-password");

    //Variaveis para acessar o banco de dados
    String database = "sosamazonia" ;
    String endereco = "jdbc:mysql://localhost:3306/" + database ;
    String usuario  = "root" ;
    String senha    = "" ;

    //Driver
    String driver   = "com.mysql.jdbc.Driver" ;

    //Carregar o Driver na memoria
    Class.forName( driver ) ;

    //Cria a variavel para conectar com o banco
    Connection conexao ;

    //Abrir a conexao com o banco de dados
    conexao = DriverManager.getConnection(endereco,usuario,senha) ;

    String sql = "INSERT INTO sosamazonia (name,email,password) VALUES (?,?,?)";

    PreparedStatement stm = conexao.prepareStatement( sql ) ;
    stm.setString( 1 , vnome ) ;
    stm.setString( 2 , vemail ) ;
    stm.setString( 3 , vsenha ) ;

    stm.execute() ;
    stm.close() ;

    out.print("<h3>Dados gravados com sucesso!</h3>") ;
    out.print("<br>") ;
    out.print("<a href='sos3.html'>Voltar</a>") ;
%>
