<tr>
	<td><?php echo $company -> name; ?></td>
	<td><?php echo $company -> address; ?></td>
	<td><?php echo number_format($company -> sum/$company -> countReviews,2); ?></td>
	<td><?php echo $company -> countReviews; ?></td>
	<td><?php echo $company -> note; ?></td>
</tr>