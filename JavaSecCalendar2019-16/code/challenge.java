import java.io.Serializable;
import javax.persistence.*;

@Entity
@DynamicUpdate
@Table(name = "UserEntity", uniqueConstraints = {
        @UniqueConstraint(columnNames = "ID"),
        @UniqueConstraint(columnNames = "EMAIL") })
public class UserEntity implements Serializable {
  public UserEntity(String email, String firstName, String lastName) {
    this.email = email;
    this.firstName = firstName;
    this.lastName = lastName;
  }

  private static final long serialVersionUID = -1798070786993154676L;

  @Id
  @GeneratedValue(strategy = GenerationType.IDENTITY)
  @Column(name = "ID", unique = true, nullable = false)
  private Integer userId;

  @Column(name = "EMAIL", unique = true, nullable = false, length = 100)
  private String email;

  @Column(name = "FIRST_NAME", unique = false, nullable = false, length = 100)
  private String firstName;

  @Column(name = "LAST_NAME", unique = false, nullable = false, length = 100)
  private String lastName;

}

//-------------------------------------------------------------------------


import org.hibernate.*;
import org.springframework.web.bind.annotation.RequestParam;

public class FindController {
  public String escapeQuotes(String in){
    return in.replaceAll("'","''");
  }

  @RequestMapping("/findUsers")
  public void findUsers(@RequestParam(name="name") String name, HttpServletResponse res) throws IOException{
    Configuration config = new Configuration();
    // Create SessionFactory with MySQL driver
    SessionFactory sessionFactory = config.configure().buildSessionFactory();
    Session session = sessionFactory.openSession();
    List <UserEntity> users = session.createQuery("from UserEntity where FIRST_NAME ='" + escapeQuotes(name) + "'", UserEntity.class).list();
    res.getWriter().println("Found " + users.size() + " Users with that name");
  }
}
